<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['court', 'user']);

        // Filtros para admin/dueño de cancha
        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin')) {
            // Admin ve todas las reservas
        } elseif (Auth::user()->hasRole('court_owner')) {
            // Dueño de cancha ve solo las de sus canchas - CORREGIDO
            $query->whereHas('court', function($q) {
                $q->where('user_id', Auth::id()); // Cambiado de owner_id a user_id
            });
        } else {
            // Cliente ve solo sus reservas
            $query->where('user_id', Auth::id());
        }

        // Aplicar filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('reservation_date', '>=', $request->date_from); // Corregido campo
        }

        if ($request->filled('date_to')) {
            $query->whereDate('reservation_date', '<=', $request->date_to); // Corregido campo
        }

        if ($request->filled('court_id')) {
            $query->where('court_id', $request->court_id);
        }

        $reservations = $query->orderBy('reservation_date', 'desc')
                             ->orderBy('start_time', 'desc')
                             ->paginate(15);

        // Para filtros en la vista
        $courts = Court::where('status', 'active')->get(); // Corregido scope
        $statuses = [
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            'completed' => 'Completada'
        ];

        return view('reservations.index', compact('reservations', 'courts', 'statuses'));
    }

    public function create(Request $request)
    {
        $court = null;
        if ($request->filled('court_id')) {
            $court = Court::findOrFail($request->court_id);
        }

        $courts = Court::where('status', 'active')->get();
        $today = Carbon::today()->format('Y-m-d');
        $maxDate = Carbon::today()->addMonths(3)->format('Y-m-d');

        return view('reservations.create', compact('courts', 'court', 'today', 'maxDate'));
    }

    public function store(StoreReservationRequest $request)
    {
        try {
            DB::beginTransaction();

            $court = Court::findOrFail($request->court_id);
            $reservationDate = $request->date ?? $request->reservation_date;
            $startTime = $request->start_time;
            $endTime = $request->end_time;

            // Calcular duración en horas
            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);
            $durationHours = $end->diffInHours($start);

            // Calcular monto total (usando precio base si no está definido)
            $pricePerHour = $court->price_per_hour ?? 50; // Precio por defecto
            $totalAmount = $durationHours * $pricePerHour;

            $reservation = new Reservation([
                'user_id' => Auth::id(),
                'court_id' => $court->id,
                'reservation_date' => $reservationDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            $reservation->save();

            DB::commit();

            return redirect()->route('reservations.show', $reservation)
                ->with('success', 'Reserva creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al crear la reserva: ' . $e->getMessage()]);
        }
    }

    public function show(Reservation $reservation)
    {
        // Verificar autorización
        if (!Auth::user()->hasAnyRole(['admin', 'super_admin'])) {
            if (Auth::user()->hasRole('court_owner')) {
                // Dueño de cancha puede ver reservas de sus canchas
                if ($reservation->court->user_id !== Auth::id()) {
                    abort(403);
                }
            } else {
                // Cliente solo puede ver sus propias reservas
                if ($reservation->user_id !== Auth::id()) {
                    abort(403);
                }
            }
        }

        $reservation->load(['court', 'user']);

        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        // Verificar autorización similar al show
        if (!Auth::user()->hasAnyRole(['admin', 'super_admin'])) {
            if (Auth::user()->hasRole('court_owner')) {
                if ($reservation->court->user_id !== Auth::id()) {
                    abort(403);
                }
            } else {
                if ($reservation->user_id !== Auth::id()) {
                    abort(403);
                }
            }
        }

        // Verificar si se puede editar (no muy cerca de la fecha)
        if ($reservation->reservation_date <= Carbon::tomorrow()) {
            return back()->withErrors(['error' => 'Esta reserva no puede ser modificada (muy cerca de la fecha).']);
        }

        $courts = Court::where('status', 'active')->get();

        return view('reservations.edit', compact('reservation', 'courts'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        // Verificar autorización
        if (!Auth::user()->hasAnyRole(['admin', 'super_admin'])) {
            if (Auth::user()->hasRole('court_owner')) {
                if ($reservation->court->user_id !== Auth::id()) {
                    abort(403);
                }
            } else {
                if ($reservation->user_id !== Auth::id()) {
                    abort(403);
                }
            }
        }

        try {
            DB::beginTransaction();

            if ($reservation->reservation_date <= Carbon::tomorrow()) {
                return back()->withErrors(['error' => 'Esta reserva no puede ser modificada.']);
            }

            $court = Court::findOrFail($request->court_id);
            $reservationDate = $request->date ?? $request->reservation_date;
            $startTime = $request->start_time;
            $endTime = $request->end_time;

            // Recalcular monto
            $start = Carbon::parse($startTime);
            $end = Carbon::parse($endTime);
            $durationHours = $end->diffInHours($start);
            $pricePerHour = $court->price_per_hour ?? 50;
            $totalAmount = $durationHours * $pricePerHour;

            $reservation->update([
                'court_id' => $request->court_id,
                'reservation_date' => $reservationDate,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'total_amount' => $totalAmount,
                'notes' => $request->notes
            ]);

            DB::commit();

            return redirect()->route('reservations.show', $reservation)
                ->with('success', 'Reserva actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al actualizar la reserva: ' . $e->getMessage()]);
        }
    }

    public function destroy(Reservation $reservation)
    {
        // Solo el usuario, el dueño de la cancha o admin pueden eliminar
        if (!Auth::user()->hasAnyRole(['admin', 'super_admin'])) {
            if (Auth::user()->hasRole('court_owner')) {
                if ($reservation->court->user_id !== Auth::id()) {
                    abort(403);
                }
            } else {
                if ($reservation->user_id !== Auth::id()) {
                    abort(403);
                }
            }
        }

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva eliminada exitosamente.');
    }

    // ======= MÉTODOS ADMINISTRATIVOS NUEVOS =======

    /**
     * Vista administrativa de todas las reservas
     */
    public function adminIndex(Request $request)
    {
        // Verificar permisos
        if (!auth()->user()->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Acceso denegado');
        }

        $query = Reservation::with(['user', 'court']);

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('court', function($courtQuery) use ($search) {
                    $courtQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('court_id')) {
            $query->where('court_id', $request->court_id);
        }

        if ($request->filled('date_from')) {
            $query->where('reservation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('reservation_date', '<=', $request->date_to);
        }

        $reservations = $query->orderBy('reservation_date', 'desc')
                             ->orderBy('start_time', 'desc')
                             ->paginate(15);

        // Estadísticas para el dashboard
        $stats = [
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')->count(),
            'cancelled_reservations' => Reservation::where('status', 'cancelled')->count(),
            'total_revenue' => Reservation::where('status', 'confirmed')->sum('total_amount'),
            'monthly_revenue' => Reservation::where('status', 'confirmed')
                ->whereMonth('reservation_date', now()->month)
                ->whereYear('reservation_date', now()->year)
                ->sum('total_amount'),
        ];

        // Obtener canchas para el filtro
        $courts = Court::orderBy('name')->get();

        return view('admin.reservations.index', compact('reservations', 'stats', 'courts'));
    }

    /**
     * Vista administrativa de reserva específica
     */
    public function adminShow(Reservation $reservation)
    {
        // Verificar permisos
        if (!auth()->user()->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Acceso denegado');
        }

        $reservation->load(['user', 'court', 'court.user']);

        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Actualizar estado de reserva (admin)
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        // Verificar permisos
        if (!auth()->user()->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Acceso denegado');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $reservation->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Estado de la reserva actualizado exitosamente.');
    }

    /**
     * Eliminar reserva (admin)
     */
    public function adminDestroy(Reservation $reservation)
    {
        // Verificar permisos
        if (!auth()->user()->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Acceso denegado');
        }

        $reservation->delete();

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reserva eliminada exitosamente.');
    }

    // ======= MÉTODOS API Y UTILIDADES =======

    /**
     * Obtener datos para calendario (API)
     */
    public function getCalendarData(Request $request)
    {
        $reservations = Reservation::with(['user', 'court'])
            ->where('reservation_date', '>=', now()->subDays(30))
            ->where('reservation_date', '<=', now()->addDays(30))
            ->get();

        $events = $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->court->name . ' - ' . $reservation->user->name,
                'start' => $reservation->reservation_date . 'T' . $reservation->start_time,
                'end' => $reservation->reservation_date . 'T' . $reservation->end_time,
                'color' => $this->getStatusColor($reservation->status),
                'url' => route('admin.reservations.show', $reservation)
            ];
        });

        return response()->json($events);
    }

    /**
     * Obtener color según estado
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'pending' => '#fbbf24',
            'confirmed' => '#10b981',
            'cancelled' => '#ef4444',
            'completed' => '#6b7280',
            default => '#6b7280'
        };
    }

    // API Methods para disponibilidad
    public function getAvailableHours(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'date' => 'required|date|after_or_equal:today'
        ]);

        $court = Court::findOrFail($request->court_id);

        // Método simplificado para obtener horas disponibles
        $availableHours = $this->generateAvailableHours($court, $request->date);

        return response()->json($availableHours);
    }

    /**
     * Generar horarios disponibles simplificado
     */
    private function generateAvailableHours($court, $date)
    {
        $availableHours = [];
        $opening = $court->opening_time;
        $closing = $court->closing_time;

        // Obtener reservas existentes para esa fecha
        $existingReservations = Reservation::where('court_id', $court->id)
            ->where('reservation_date', $date)
            ->where('status', '!=', 'cancelled')
            ->get(['start_time', 'end_time']);

        // Generar intervalos de 1 hora básicos
        $current = Carbon::createFromFormat('H:i:s', $opening);
        $end = Carbon::createFromFormat('H:i:s', $closing);

        while ($current < $end) {
            $nextHour = $current->copy()->addHour();

            // Verificar si este horario está disponible
            $isAvailable = true;
            foreach ($existingReservations as $reservation) {
                $reservStart = Carbon::createFromFormat('H:i:s', $reservation->start_time);
                $reservEnd = Carbon::createFromFormat('H:i:s', $reservation->end_time);

                // Si hay conflicto
                if ($current < $reservEnd && $nextHour > $reservStart) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableHours[] = [
                    'start' => $current->format('H:i'),
                    'end' => $nextHour->format('H:i'),
                    'display' => $current->format('H:i') . ' - ' . $nextHour->format('H:i')
                ];
            }

            $current = $nextHour;
        }

        return $availableHours;
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'court_id' => 'required|exists:courts,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'exclude_id' => 'nullable|exists:reservations,id'
        ]);

        $query = Reservation::where('court_id', $request->court_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                  ->orWhere(function($subQ) use ($request) {
                      $subQ->where('start_time', '<=', $request->start_time)
                           ->where('end_time', '>=', $request->end_time);
                  });
            });

        if ($request->exclude_id) {
            $query->where('id', '!=', $request->exclude_id);
        }

        $hasConflict = $query->exists();

        return response()->json(['available' => !$hasConflict]);
    }

    // Métodos para reportes
    public function monthlyReport(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $query = Reservation::with(['court', 'user'])
            ->whereMonth('reservation_date', $month)
            ->whereYear('reservation_date', $year);

        // Aplicar filtros según rol
        if (Auth::user()->hasRole('court_owner')) {
            $query->whereHas('court', function($q) {
                $q->where('user_id', Auth::id());
            });
        }

        $reservations = $query->get();

        $stats = [
            'total_reservations' => $reservations->count(),
            'total_revenue' => $reservations->where('status', 'confirmed')->sum('total_amount'),
            'pending_reservations' => $reservations->where('status', 'pending')->count(),
            'confirmed_reservations' => $reservations->where('status', 'confirmed')->count(),
            'cancelled_reservations' => $reservations->where('status', 'cancelled')->count(),
            'completed_reservations' => $reservations->where('status', 'completed')->count(),
        ];

        return view('reservations.monthly-report', compact('reservations', 'stats', 'month', 'year'));
    }
}
