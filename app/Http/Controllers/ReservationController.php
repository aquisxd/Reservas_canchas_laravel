<?php
// app/Http/Controllers/ReservationController.php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Court;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->isAdmin()
            ? Reservation::with(['user', 'court'])->latest()->paginate(15)
            : auth()->user()->reservations()->with('court')->latest()->paginate(15);

        return view('reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $court = null;
        if ($request->has('court_id')) {
            $court = Court::findOrFail($request->court_id);
        }

        $courts = Court::active()->get();
        return view('reservations.create', compact('courts', 'court'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'notes' => 'nullable|string'
        ]);

        // Verificar disponibilidad
        $existing = Reservation::where('court_id', $validated['court_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($existing) {
            return back()->withErrors(['time' => 'El horario seleccionado no está disponible.']);
        }

        // Calcular precio
        $court = Court::findOrFail($validated['court_id']);
        $startTime = Carbon::createFromFormat('H:i', $validated['start_time']);
        $endTime = Carbon::createFromFormat('H:i', $validated['end_time']);
        $hours = $endTime->diffInHours($startTime);

        $validated['user_id'] = auth()->id();
        $validated['total_amount'] = $court->price_per_hour * $hours;
        $validated['status'] = 'pending';

        Reservation::create($validated);

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva creada exitosamente.');
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        $reservation->load(['user', 'court']);

        return view('reservations.show', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $reservation->update($validated);

        return back()->with('success', 'Estado de reserva actualizado.');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva eliminada exitosamente.');
    }
}
