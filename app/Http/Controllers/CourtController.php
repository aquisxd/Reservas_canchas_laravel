<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar canchas para propietarios y admins
     */
    public function index(Request $request)
    {
       $this->authorize('viewAny', Court::class);

        $query = Court::with(['user', 'reservations']);

        // Los propietarios solo ven sus canchas, los admins ven todas
        if (auth()->user()->hasRole('court_owner') && !auth()->user()->hasRole(['admin', 'super_admin'])) {
            $query->where('user_id', auth()->id());
        }

        // Filtros adaptados a los campos disponibles
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', $request->capacity);
        }

        $courts = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('courts.index', compact('courts'));
    }

    /**
     * Mostrar canchas públicas para clientes
     */
    public function publicIndex(Request $request)
    {
        $query = Court::with(['user'])->active();

        // Filtros públicos adaptados
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', $request->capacity);
        }

        $courts = $query->orderBy('name')->paginate(12);

        return view('courts.public-index', compact('courts'));
    }

    /**
     * Mostrar una cancha específica
     */
    public function show(Court $court)
    {
        //$this->authorize('view', $court);

        $court->load(['user', 'reservations' => function($query) {
            $query->where('reservation_date', '>=', today())
                  ->orderBy('reservation_date', 'asc')
                  ->orderBy('start_time', 'asc');
        }]);

        // Obtener horarios disponibles para los próximos 7 días
        $availableSchedule = [];
        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $availableSchedule[$date] = $court->getAvailableHours($date);
        }

        return view('courts.show', compact('court', 'availableSchedule'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
       // $this->authorize('create', Court::class);

        return view('courts.create');
    }

    /**
     * Almacenar nueva cancha - ADAPTADO A CAMPOS DISPONIBLES
     */
    public function store(Request $request)
    {
        $this->authorize('create', Court::class);

        // Validación solo de campos que existen en la migración
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'amenities' => 'nullable|array',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'capacity' => 'required|integer|min:2|max:50',
            'status' => 'required|in:active,inactive,maintenance',
            'rules' => 'nullable|string|max:2000',
        ]);

        $data = $request->only([
            'name',
            'description',
            'location',
            'phone',
            'amenities',
            'opening_time',
            'closing_time',
            'capacity',
            'status',
            'rules'
        ]);

        // Asignar el propietario
        if (auth()->user()->hasRole(['admin', 'super_admin']) && $request->filled('user_id')) {
            $data['user_id'] = $request->user_id;
        } else {
            $data['user_id'] = auth()->id();
        }

        // Convertir amenities a JSON si viene como array
        if (isset($data['amenities']) && is_array($data['amenities'])) {
            $data['amenities'] = json_encode($data['amenities']);
        }

        $court = Court::create($data);

        return redirect()->route('courts.show', $court)
            ->with('success', 'Cancha creada exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Court $court)
    {
        $this->authorize('update', $court);

        return view('courts.edit', compact('court'));
    }

    /**
     * Actualizar cancha - ADAPTADO A CAMPOS DISPONIBLES
     */
    public function update(Request $request, Court $court)
    {
        $this->authorize('update', $court);

        // Validación solo de campos que existen
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'amenities' => 'nullable|array',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'capacity' => 'required|integer|min:2|max:50',
            'status' => 'required|in:active,inactive,maintenance',
            'rules' => 'nullable|string|max:2000',
        ]);

        $data = $request->only([
            'name',
            'description',
            'location',
            'phone',
            'amenities',
            'opening_time',
            'closing_time',
            'capacity',
            'status',
            'rules'
        ]);

        // Convertir amenities a JSON si viene como array
        if (isset($data['amenities']) && is_array($data['amenities'])) {
            $data['amenities'] = json_encode($data['amenities']);
        }

        $court->update($data);

        return redirect()->route('courts.show', $court)
            ->with('success', 'Cancha actualizada exitosamente.');
    }

    /**
     * Eliminar cancha
     */
    public function destroy(Court $court)
    {
        $this->authorize('delete', $court);

        // Verificar si tiene reservas futuras
        $futureReservations = $court->reservations()
            ->where('reservation_date', '>=', today())
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($futureReservations > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar la cancha porque tiene reservas futuras.');
        }

        $court->delete();

        return redirect()->route('courts.index')
            ->with('success', 'Cancha eliminada exitosamente.');
    }

    /**
     * Cambiar estado de la cancha (para admins) - Método administrativo
     */
    public function adminToggleStatus(Court $court)
    {
        $this->authorize('manageStatus', $court);

        $statusMap = [
            'active' => 'inactive',
            'inactive' => 'maintenance',
            'maintenance' => 'active'
        ];

        $newStatus = $statusMap[$court->status] ?? 'active';
        $court->update(['status' => $newStatus]);

        $statusMessages = [
            'active' => 'Cancha activada',
            'inactive' => 'Cancha desactivada',
            'maintenance' => 'Cancha en mantenimiento'
        ];

        $message = $statusMessages[$newStatus] ?? 'Estado cambiado';

        return redirect()->back()->with('success', $message . ' exitosamente.');
    }

    /**
     * Ver reservas de una cancha
     */
    public function reservations(Court $court)
    {
        $this->authorize('viewReservations', $court);

        $reservations = $court->reservations()
            ->with('user')
            ->orderBy('reservation_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        return view('courts.reservations', compact('court', 'reservations'));
    }

    /**
     * Aprobar reserva
     */
    public function approveReservation(Court $court, Reservation $reservation)
    {
        $this->authorize('manageReservations', $court);

        if ($reservation->court_id !== $court->id) {
            abort(404);
        }

        $reservation->update(['status' => 'confirmed']);

        return redirect()->back()
            ->with('success', 'Reserva aprobada exitosamente.');
    }

    /**
     * Rechazar reserva
     */
    public function rejectReservation(Court $court, Reservation $reservation)
    {
        $this->authorize('manageReservations', $court);

        if ($reservation->court_id !== $court->id) {
            abort(404);
        }

        $reservation->update(['status' => 'cancelled']);

        return redirect()->back()
            ->with('success', 'Reserva rechazada exitosamente.');
    }

    /**
     * Obtener horarios disponibles (API) - SIMPLIFICADO
     */
    public function getAvailableTimes(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $court = Court::findOrFail($request->court_id);

        // Método simplificado si no tienes getAvailableHours en el modelo
        $availableHours = $this->generateAvailableHours($court, $request->date);

        return response()->json([
            'success' => true,
            'available_times' => $availableHours
        ]);
    }

    /**
     * Generar horarios disponibles simplificado
     */
    private function generateAvailableHours($court, $date)
    {
        $availableHours = [];
        $opening = $court->opening_time;
        $closing = $court->closing_time;

        // Generar intervalos de 1 hora básicos
        $current = \Carbon\Carbon::createFromFormat('H:i:s', $opening);
        $end = \Carbon\Carbon::createFromFormat('H:i:s', $closing);

        while ($current < $end) {
            $nextHour = $current->copy()->addHour();

            $availableHours[] = [
                'start' => $current->format('H:i'),
                'end' => $nextHour->format('H:i'),
                'display' => $current->format('H:i') . ' - ' . $nextHour->format('H:i')
            ];

            $current = $nextHour;
        }

        return $availableHours;
    }

    // Métodos para administradores - ADAPTADOS

    /**
     * Vista administrativa de canchas
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('viewAny', Court::class);

        $query = Court::with(['user', 'reservations']);

        // Filtros adaptados
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('owner_id')) {
            $query->where('user_id', $request->owner_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('capacity')) {
            $query->where('capacity', $request->capacity);
        }

        $courts = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.courts.index', compact('courts'));
    }

    /**
     * Vista administrativa de cancha específica
     */
    /**
 * Vista administrativa de cancha específica - CORREGIDO
 */
public function adminShow(Court $court)
{
    $this->authorize('view', $court);

    $court->load(['user', 'reservations.user']);

    // Estadísticas usando total_amount (no total_price)
    $stats = [
        'total_reservations' => $court->reservations()->count(),
        'confirmed_reservations' => $court->reservations()->where('status', 'confirmed')->count(),
        'pending_reservations' => $court->reservations()->where('status', 'pending')->count(),
        'cancelled_reservations' => $court->reservations()->where('status', 'cancelled')->count(),

        // Estadísticas del mes actual usando total_amount
        'monthly_reservations' => $court->reservations()
            ->whereMonth('reservation_date', now()->month)
            ->whereYear('reservation_date', now()->year)
            ->count(),

        'monthly_confirmed' => $court->reservations()
            ->where('status', 'confirmed')
            ->whereMonth('reservation_date', now()->month)
            ->whereYear('reservation_date', now()->year)
            ->count(),

        // Revenue real usando total_amount
        'total_revenue' => $court->reservations()
            ->where('status', 'confirmed')
            ->sum('total_amount'),

        'monthly_revenue' => $court->reservations()
            ->where('status', 'confirmed')
            ->whereMonth('reservation_date', now()->month)
            ->whereYear('reservation_date', now()->year)
            ->sum('total_amount'),

        // Últimas reservas
        'recent_reservations' => $court->reservations()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(),
    ];

    return view('admin.courts.show', compact('court', 'stats'));
}
    /**
     * Eliminar cancha (admin)
     */
    public function adminDestroy(Court $court)
    {
        $this->authorize('forceDelete', $court);

        $court->delete();

        return redirect()->route('admin.courts.index')
            ->with('success', 'Cancha eliminada exitosamente.');
    }
}
