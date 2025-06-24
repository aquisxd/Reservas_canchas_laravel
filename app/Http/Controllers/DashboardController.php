<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard principal - redirige según el rol del usuario
     */
    public function index()
    {
        try {
            $user = Auth::user();

            Log::info('=== INICIO DASHBOARD INDEX ===', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
            ]);

            // Actualizar último login
            if (method_exists($user, 'updateLastLogin')) {
                $user->updateLastLogin();
            }

            // VERSIÓN SIMPLIFICADA: Cargar dashboard directamente según rol
            // Verificar roles admin
            if ($user->hasAnyRole(['super_admin', 'admin'])) {
                Log::info('=== USUARIO ES ADMIN - CARGANDO ADMIN DASHBOARD ===', ['user_id' => $user->id]);
                return $this->adminDashboard();
            }

            // Verificar rol específico de court_owner
            if ($user->hasRole('court_owner')) {
                Log::info('=== USUARIO ES COURT OWNER ===', ['user_id' => $user->id]);
                return $this->ownerDashboard();
            }

            // Por defecto, dashboard de cliente
            Log::info('=== USUARIO ES CLIENTE ===', ['user_id' => $user->id]);
            return $this->clientDashboard();

        } catch (\Exception $e) {
            Log::error('=== ERROR EN DASHBOARD INDEX ===', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id()
            ]);

            // En caso de error, mostrar dashboard de cliente
            return $this->clientDashboard();
        }
    }

    /**
     * Dashboard administrativo - Método público para acceso directo
     */
    public function admin()
    {
        // Verificación manual de permisos (sin middleware problemático)
        if (!auth()->user()->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'No tienes permisos de administrador.');
        }

        Log::info('=== ACCEDIENDO A ADMIN() ===', [
            'user_id' => auth()->id(),
            'roles' => auth()->user()->getRoleNames()->toArray(),
        ]);

        return $this->adminDashboard();
    }

    /**
     * Lógica del dashboard administrativo
     */
    private function adminDashboard()
    {
        try {
            Log::info('=== DENTRO DE adminDashboard() ===');

            $user = Auth::user();

            // Estadísticas principales
            $stats = [
                'total_users' => User::count(),
                'total_courts' => Court::count(),
                'total_reservations' => Reservation::count(),
                'today_reservations' => Reservation::whereDate('date', today())->count(),
                'monthly_revenue' => Reservation::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('payment_status', 'completed')
                    ->sum('total_price') ?? 0,
                'pending_reservations' => Reservation::where('status', 'pending')->count(),
                'active_courts' => Court::where('is_active', true)->count(),
                'new_users_this_month' => User::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ];

            Log::info('=== ESTADÍSTICAS CALCULADAS ===', $stats);

            // Usuarios por rol
            $usersByRole = [];
            $roles = Role::all();
            foreach ($roles as $role) {
                $usersByRole[$role->name] = User::role($role->name)->count();
            }

            // Reservas pendientes de aprobación
            $pendingReservations = Reservation::with(['user', 'court'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Actividad reciente
            $recentActivity = $this->getRecentActivity();

            // Alertas del sistema
            $alerts = $this->getSystemAlerts();

            Log::info('=== CARGANDO VISTA dashboard.admin ===', ['user_id' => $user->id]);

            return view('dashboard.admin', compact(
                'stats',
                'usersByRole',
                'pendingReservations',
                'recentActivity',
                'alerts'
            ));

        } catch (\Exception $e) {
            Log::error('=== ERROR EN adminDashboard() ===', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'user_id' => auth()->id()
            ]);

            // Dashboard simplificado en caso de error
            return view('dashboard.admin', [
                'stats' => [
                    'total_users' => User::count() ?? 0,
                    'total_courts' => Court::count() ?? 0,
                    'total_reservations' => Reservation::count() ?? 0,
                    'today_reservations' => 0,
                    'monthly_revenue' => 0,
                    'pending_reservations' => 0,
                    'active_courts' => 0,
                    'new_users_this_month' => 0,
                ],
                'usersByRole' => [],
                'pendingReservations' => collect(),
                'recentActivity' => [],
                'alerts' => []
            ]);
        }
    }

    /**
     * Dashboard para propietarios de canchas
     */
    private function ownerDashboard()
    {
        try {
            $user = Auth::user();

            // Verificar que el usuario tenga el rol correcto
            if (!$user->hasRole('court_owner')) {
                Log::warning('Usuario sin rol court_owner intentando acceder a owner dashboard', [
                    'user_id' => $user->id,
                    'roles' => $user->getRoleNames()->toArray()
                ]);
                return $this->clientDashboard();
            }

            // Verificar que exista la relación courts en el modelo User
            if (!method_exists($user, 'courts')) {
                Log::error('Relación courts no existe en el modelo User');
                throw new \Exception('Relación courts no configurada en el modelo User');
            }

            $courts = $user->courts;
            $courtIds = $courts->pluck('id');

            $stats = [
                'total_courts' => $courts->count(),
                'active_courts' => $courts->where('is_active', true)->count(),
                'total_reservations' => Reservation::whereIn('court_id', $courtIds)->count(),
                'pending_reservations' => Reservation::whereIn('court_id', $courtIds)
                    ->where('status', 'pending')
                    ->count(),
                'monthly_revenue' => Reservation::whereIn('court_id', $courtIds)
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->where('payment_status', 'completed')
                    ->sum('total_price') ?? 0,
                'today_reservations' => Reservation::whereIn('court_id', $courtIds)
                    ->whereDate('date', today())
                    ->count(),
            ];

            // Reservas recientes en sus canchas
            $recentReservations = Reservation::with(['user', 'court'])
                ->whereIn('court_id', $courtIds)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            // Canchas más reservadas
            $popularCourts = Court::withCount(['reservations' => function ($query) {
                    $query->whereMonth('created_at', now()->month);
                }])
                ->where('owner_id', $user->id)
                ->orderBy('reservations_count', 'desc')
                ->take(5)
                ->get();

            Log::info('Cargando owner dashboard exitosamente', ['user_id' => $user->id]);

            return view('dashboard.court-owner', compact(
                'stats',
                'courts',
                'recentReservations',
                'popularCourts'
            ));

        } catch (\Exception $e) {
            Log::error('Error en owner dashboard', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return view('dashboard.court-owner', [
                'stats' => [
                    'total_courts' => 0,
                    'active_courts' => 0,
                    'total_reservations' => 0,
                    'pending_reservations' => 0,
                    'monthly_revenue' => 0,
                    'today_reservations' => 0,
                ],
                'courts' => collect(),
                'recentReservations' => collect(),
                'popularCourts' => collect()
            ]);
        }
    }

    /**
     * Dashboard para clientes
     */
    private function clientDashboard()
    {
        try {
            $user = Auth::user();

            Log::info('Cargando client dashboard', [
                'user_id' => $user->id,
                'roles' => $user->getRoleNames()->toArray()
            ]);

            $stats = [
                'total_reservations' => $user->reservations()->count(),
                'upcoming_reservations' => $user->reservations()
                    ->where('date', '>=', today())
                    ->where('status', '!=', 'cancelled')
                    ->count(),
                'completed_reservations' => $user->reservations()
                    ->where('status', 'completed')
                    ->count(),
                'total_spent' => $user->reservations()
                    ->where('payment_status', 'completed')
                    ->sum('total_price') ?? 0,
                'this_month_reservations' => $user->reservations()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ];

            // Próximas reservas
            $upcomingReservations = $user->reservations()
                ->with('court')
                ->where('date', '>=', today())
                ->where('status', '!=', 'cancelled')
                ->orderBy('date')
                ->orderBy('start_time')
                ->take(5)
                ->get();

            // Canchas favoritas (más reservadas por el usuario)
            $favoriteCourts = Court::withCount(['reservations' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }])
                ->having('reservations_count', '>', 0)
                ->orderBy('reservations_count', 'desc')
                ->take(3)
                ->get();

            // Historial reciente
            $recentReservations = $user->reservations()
                ->with('court')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            Log::info('Client dashboard cargado exitosamente', ['user_id' => $user->id]);

            return view('dashboard.client', compact(
                'stats',
                'upcomingReservations',
                'favoriteCourts',
                'recentReservations'
            ));

        } catch (\Exception $e) {
            Log::error('Error en client dashboard', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return view('dashboard.client', [
                'stats' => [
                    'total_reservations' => 0,
                    'upcoming_reservations' => 0,
                    'completed_reservations' => 0,
                    'total_spent' => 0,
                    'this_month_reservations' => 0,
                ],
                'upcomingReservations' => collect(),
                'favoriteCourts' => collect(),
                'recentReservations' => collect()
            ]);
        }
    }

    /**
     * Vista de reportes (solo admins)
     */
    public function reports()
    {
        // Verificación manual de permisos
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'No tiene permisos para ver reportes.');
        }

        try {
            // Estadísticas mensuales
            $monthlyStats = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $monthlyStats[] = [
                    'month' => $date->format('M Y'),
                    'reservations' => Reservation::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                    'revenue' => Reservation::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->where('payment_status', 'completed')
                        ->sum('total_price') ?? 0,
                    'users' => User::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count(),
                ];
            }

            // Top canchas por reservas
            $topCourts = Court::withCount('reservations')
                ->orderBy('reservations_count', 'desc')
                ->take(10)
                ->get();

            // Usuarios más activos
            $topUsers = User::withCount('reservations')
                ->orderBy('reservations_count', 'desc')
                ->take(10)
                ->get();

            return view('admin.reports', compact(
                'monthlyStats',
                'topCourts',
                'topUsers'
            ));

        } catch (\Exception $e) {
            Log::error('Error en reports', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error al cargar los reportes: ' . $e->getMessage());
        }
    }

    /**
     * Exportar reportes
     */
    public function exportReports()
    {
        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            abort(403, 'No tiene permisos para exportar reportes.');
        }

        // Aquí implementarías la lógica de exportación (Excel, PDF, etc.)
        return back()->with('success', 'Funcionalidad de exportación en desarrollo');
    }

    /**
     * Obtener actividad reciente del sistema
     */
    private function getRecentActivity()
    {
        try {
            $activities = [];

            // Últimos usuarios registrados
            $newUsers = User::orderBy('created_at', 'desc')->take(3)->get();
            foreach ($newUsers as $user) {
                $activities[] = [
                    'description' => 'Nuevo usuario registrado',
                    'user' => $user->name,
                    'time' => $user->created_at->diffForHumans(),
                    'created_at' => $user->created_at
                ];
            }

            // Últimas reservas
            $newReservations = Reservation::with('user')->orderBy('created_at', 'desc')->take(3)->get();
            foreach ($newReservations as $reservation) {
                $activities[] = [
                    'description' => 'Nueva reserva creada',
                    'user' => $reservation->user->name,
                    'time' => $reservation->created_at->diffForHumans(),
                    'created_at' => $reservation->created_at
                ];
            }

            // Ordenar por fecha de creación
            usort($activities, function ($a, $b) {
                return $b['created_at']->timestamp - $a['created_at']->timestamp;
            });

            return array_slice($activities, 0, 5);

        } catch (\Exception $e) {
            Log::error('Error obteniendo actividad reciente', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtener alertas del sistema
     */
    private function getSystemAlerts()
    {
        try {
            $alerts = [];

            // Verificar usuarios inactivos
            $inactiveUsers = User::where('is_active', false)->count();
            if ($inactiveUsers > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'Usuarios Inactivos',
                    'message' => "Hay {$inactiveUsers} usuarios inactivos en el sistema",
                    'action' => [
                        'text' => 'Ver usuarios',
                        'url' => '#' // Cambiar por la ruta real cuando exista
                    ]
                ];
            }

            // Verificar reservas pendientes
            $pendingReservations = Reservation::where('status', 'pending')->count();
            if ($pendingReservations > 5) {
                $alerts[] = [
                    'type' => 'warning',
                    'title' => 'Muchas Reservas Pendientes',
                    'message' => "Hay {$pendingReservations} reservas esperando aprobación",
                    'action' => [
                        'text' => 'Ver reservas',
                        'url' => '#' // Cambiar por la ruta real cuando exista
                    ]
                ];
            }

            // Verificar canchas inactivas
            $inactiveCourts = Court::where('is_active', false)->count();
            if ($inactiveCourts > 0) {
                $alerts[] = [
                    'type' => 'info',
                    'title' => 'Canchas Inactivas',
                    'message' => "Hay {$inactiveCourts} canchas marcadas como inactivas",
                    'action' => [
                        'text' => 'Ver canchas',
                        'url' => '#' // Cambiar por la ruta real cuando exista
                    ]
                ];
            }

            return $alerts;

        } catch (\Exception $e) {
            Log::error('Error obteniendo alertas del sistema', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Método para debug - eliminar en producción
     */
    public function debugRoles()
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $user = Auth::user();

        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'roles' => $user->roles->pluck('name')->toArray(),
            'role_names' => $user->getRoleNames()->toArray(),
            'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
            'has_admin_role' => $user->hasRole('admin'),
            'has_super_admin_role' => $user->hasRole('super_admin'),
            'has_court_owner_role' => $user->hasRole('court_owner'),
            'has_any_admin_role' => $user->hasAnyRole(['super_admin', 'admin']),
        ]);
    }
}
