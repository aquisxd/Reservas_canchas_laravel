<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\DashboardController;

// ❌ ESTA LÍNEA DEBE SER ELIMINADA
// use Illuminate\Support\Facades\Auth as FacadesAuth;
// FacadesAuth::routes();

// Laravel Breeze ya define las rutas de autenticación en routes/auth.php

Route::get('/', function () {
    return view('welcome');
})->name('home');
// Si quieres que los usuarios autenticados vayan directo al dashboard
Route::get('/home', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('home');
});

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Courts - solo admin puede crear, editar y eliminar
    Route::get('/courts', [CourtController::class, 'index'])->name('courts.index');
    Route::get('/courts/{court}', [CourtController::class, 'show'])->name('courts.show');

    Route::middleware('admin')->group(function () {
        Route::get('/courts/create', [CourtController::class, 'create'])->name('courts.create');
        Route::post('/courts', [CourtController::class, 'store'])->name('courts.store');
        Route::get('/courts/{court}/edit', [CourtController::class, 'edit'])->name('courts.edit');
        Route::put('/courts/{court}', [CourtController::class, 'update'])->name('courts.update');
        Route::delete('/courts/{court}', [CourtController::class, 'destroy'])->name('courts.destroy');
    });

    // Reservations
    Route::resource('reservations', ReservationController::class);
});

require __DIR__.'/auth.php';
