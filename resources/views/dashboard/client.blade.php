<!-- resources/views/dashboard/client.blade.php -->
@extends('layouts.app')
<!-- Welcome Hero Section -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card shadow" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <div class="card-body text-white">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="mb-3">¡Hola, {{ Auth::user()->name }}!</h2>
                        <p class="mb-4 lead">Bienvenido a tu panel personal de reservas. Aquí puedes gestionar todas tus
                            reservas de canchas de tenis de forma fácil y rápida.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('reservations.create') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-plus me-2"></i>Nueva Reserva
                            </a>
                            <a href="{{ route('courts.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-map-marker-alt me-2"></i>Ver Canchas
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <i class="fas fa-user-circle fa-6x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Reservas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['total_reservations'] ?? 0 }}
                        </div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-primary mr-2">
                                <i class="fas fa-calendar"></i> Historial completo
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Próximas Reservas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['upcoming_reservations'] ?? 0 }}
                        </div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success mr-2">
                                <i class="fas fa-arrow-up"></i> Por confirmar
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Completadas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['completed_reservations'] ?? 0 }}
                        </div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-info mr-2">
                                <i class="fas fa-check-circle"></i> Finalizadas
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Cancha Favorita
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                            {{ $stats['favorite_court'] ?? 'N/A' }}
                        </div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-warning mr-2">
                                <i class="fas fa-star"></i> Más reservada
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-star fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg w-100 shadow-sm">
                            <i class="fas fa-plus-circle fa-2x d-block mb-2"></i>
                            <span class="d-block">Nueva Reserva</span>
                            <small class="d-block text-white-50">Reservar cancha ahora</small>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('courts.index') }}" class="btn btn-success btn-lg w-100 shadow-sm">
                            <i class="fas fa-map-marker-alt fa-2x d-block mb-2"></i>
                            <span class="d-block">Explorar Canchas</span>
                            <small class="d-block text-white-50">Ver todas las opciones</small>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-3">
                        <a href="{{ route('reservations.index') }}" class="btn btn-info btn-lg w-100 shadow-sm">
                            <i class="fas fa-history fa-2x d-block mb-2"></i>
                            <span class="d-block">Mi Historial</span>
                            <small class="d-block text-white-50">Ver todas mis reservas</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Reservations -->
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar-alt me-2"></i>Próximas Reservas
                </h6>
                <a href="{{ route('reservations.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-eye me-1"></i>Ver Todas
                </a>
            </div>
            <div class="card-body">
                @if(isset($upcoming_reservations) && $upcoming_reservations->count() > 0)
                    @foreach($upcoming_reservations as $reservation)
                        <div
                            class="card mb-3 border-left-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : 'danger') }}">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <div
                                                    class="icon-circle bg-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : 'danger') }}">
                                                    <i class="fas fa-tennis-ball text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="font-weight-bold mb-1">{{ $reservation->court->name }}</h6>
                                                <p class="mb-1 text-muted small">
                                                    <i
                                                        class="fas fa-calendar me-1"></i>{{ $reservation->reservation_date->format('d/m/Y') }}
                                                    |
                                                    <i class="fas fa-clock me-1"></i>{{ $reservation->start_time }} -
                                                    {{ $reservation->end_time }}
                                                </p>
                                                <span
                                                    class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($reservation->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-right">
                                        <div class="h5 mb-1 font-weight-bold text-success">
                                            ${{ number_format($reservation->total_amount, 0) }}
                                        </div>
                                        <a href="{{ route('reservations.show', $reservation) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tienes reservas próximas</h5>
                        <p class="text-muted">¡Haz tu primera reserva y disfruta del mejor tenis!</p>
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Crear Primera Reserva
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tips & Info -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-lightbulb me-2"></i>Consejos Útiles
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-clock text-primary mr-2"></i>
                        <small class="font-weight-bold">Reserva con Anticipación</small>
                    </div>
                    <small class="text-muted">Te recomendamos reservar con al menos 24 horas de anticipación para
                        asegurar disponibilidad.</small>
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-weather-sun text-warning mr-2"></i>
                        <small class="font-weight-bold">Verifica el Clima</small>
                    </div>
                    <small class="text-muted">Revisa el pronóstico del tiempo antes de tu reserva para una mejor
                        experiencia.</small>
                </div>

                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-mobile-alt text-success mr-2"></i>
                        <small class="font-weight-bold">Confirma tu Reserva</small>
                    </div>
                    <small class="text-muted">