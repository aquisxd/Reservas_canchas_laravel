<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Ruta de debug - TEMPORAL (puedes eliminarla después)
Route::get('/debug-user', function() {
    if (!auth()->check()) {
        return 'Usuario no logueado';
    }

    $user = auth()->user();
    return response()->json([
        'user_id' => $user->id,
        'user' => $user->name,
        'email' => $user->email,
        'roles' => $user->getRoleNames(),
        'has_admin' => $user->hasRole('admin'),
        'has_super_admin' => $user->hasRole('super_admin'),
        'has_any_admin' => $user->hasAnyRole(['admin', 'super_admin']),
        'can_view_admin_dashboard' => $user->can('view admin dashboard'),
        'current_route' => request()->route()->getName()
    ]);
})->middleware('auth');

// Rutas protegidas que requieren autenticación
Route::middleware('auth')->group(function () {

    // Profile routes - todos los usuarios autenticados
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Reservas - SIMPLIFICADAS (verificación en controlador)
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');

    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    // Rutas de Canchas - SIMPLIFICADAS (verificación en controlador)
    Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
    Route::get('/courts/create', [CourtController::class, 'create'])->name('courts.create');
    Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');

    Route::post('/courts', [CourtController::class, 'store'])->name('courts.store');
    Route::get('/courts/{court}/edit', [CourtController::class, 'edit'])->name('courts.edit');
    Route::patch('/courts/{court}', [CourtController::class, 'update'])->name('courts.update');
    Route::delete('/courts/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');

    // Gestión de reservas de canchas
    Route::get('/courts/{court}/reservations', [CourtController::class, 'reservations'])->name('courts.reservations');
    Route::patch('/courts/{court}/reservations/{reservation}/approve', [CourtController::class, 'approveReservation'])->name('courts.reservations.approve');
    Route::patch('/courts/{court}/reservations/{reservation}/reject', [CourtController::class, 'rejectReservation'])->name('courts.reservations.reject');
});

// Rutas de Administración - SIN MIDDLEWARE PROBLEMÁTICO
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard administrativo
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Reportes y estadísticas
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export', [DashboardController::class, 'exportReports'])->name('reports.export');

    // Gestión de usuarios - RUTAS BÁSICAS
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('/users/{user}/change-roles', [UserManagementController::class, 'changeRoles'])->name('users.change-roles');
    Route::patch('/users/{user}/assign-permissions', [UserManagementController::class, 'assignPermissions'])->name('users.assign-permissions');

    // Gestión completa de canchas - ADMIN SIN MIDDLEWARE PROBLEMÁTICO
    Route::get('/courts', [CourtController::class, 'adminIndex'])->name('courts.index');
    Route::get('/courts/{court}', [CourtController::class, 'adminShow'])->name('courts.show');
    Route::delete('/courts/{court}', [CourtController::class, 'adminDestroy'])->name('courts.destroy');
    Route::patch('/courts/{court}/toggle-status', [CourtController::class, 'adminToggleStatus'])->name('courts.toggle-status');

    // Gestión completa de reservas - ADMIN
    Route::get('/reservations', [ReservationController::class, 'adminIndex'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'adminShow'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.update-status');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'adminDestroy'])->name('reservations.destroy');

    // Gestión de roles y permisos - BÁSICO
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::patch('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::patch('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});

// Rutas API para funcionalidades AJAX - SIMPLIFICADAS
Route::prefix('api')->middleware('auth')->group(function () {
    Route::get('/courts/available-times', [CourtController::class, 'getAvailableTimes'])
        ->name('api.courts.available-times');
    Route::get('/reservations/calendar', [ReservationController::class, 'getCalendarData'])
        ->name('api.reservations.calendar');
});

require __DIR__.'/auth.php';
