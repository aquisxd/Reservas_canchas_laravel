<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->clientDashboard();
    }

    private function adminDashboard()
    {
        $stats = [
            'total_courts' => Court::count(),
            'active_courts' => Court::active()->count(),
            'total_users' => User::where('role', 'client')->count(),
            'today_reservations' => Reservation::whereDate('reservation_date', Carbon::today())->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'monthly_revenue' => Reservation::whereMonth('created_at', Carbon::now()->month)
                ->where('status', 'completed')
                ->sum('total_amount')
        ];

        $recent_reservations = Reservation::with(['user', 'court'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'recent_reservations'));
    }

    private function clientDashboard()
{
    $user = auth()->user();

    $upcoming_reservations = $user->reservations()
        ->with('court')
        ->upcoming()
        ->orderBy('reservation_date')
        ->orderBy('start_time')
        ->limit(5)
        ->get();

    // Obtener cancha favorita (más reservada)
    $favorite_court = $user->reservations()
        ->select('court_id', DB::raw('count(*) as total'))
        ->groupBy('court_id')
        ->orderBy('total', 'desc')
        ->with('court')
        ->first();

    $stats = [
        'total_reservations' => $user->reservations()->count(),
        'upcoming_reservations' => $user->reservations()->upcoming()->count(),
        'completed_reservations' => $user->reservations()->where('status', 'completed')->count(),
        'favorite_court' => $favorite_court ? $favorite_court->court->name : 'N/A'
    ];

    return view('dashboard.client', compact('upcoming_reservations', 'stats'));
}




}
