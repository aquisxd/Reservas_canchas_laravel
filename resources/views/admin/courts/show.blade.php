<!-- resources/views/courts/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">{{ $court->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('courts.index') }}">Canchas</a></li>
                        <li class="breadcrumb-item active">{{ $court->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-group" role="group">
                <a href="{{ route('courts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
                @if($court->is_active)
                    <a href="{{ route('reservations.create', ['court_id' => $court->id]) }}" class="btn btn-success">
                        <i class="fas fa-calendar-plus me-2"></i>Reservar
                    </a>
                @endif
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('courts.edit', $court) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                @endif
            </div>
        </div>

        <div class="row">
            <!-- Court Information -->
            <div class="col-lg-8">
                <!-- Main Court Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Información de la Cancha
                                </h6>
                            </div>
                            <div class="col-auto">
                                @if($court->is_active)
                                    <span class="badge badge-success badge-lg">
                                        <i class="fas fa-check-circle me-1"></i>Disponible
                                    </span>
                                @else
                                    <span class="badge badge-danger badge-lg">
                                        <i class="fas fa-times-circle me-1"></i>No Disponible
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Court Image -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="court-image-container mb-3"
                                    style="height: 300px; border-radius: 10px; overflow: hidden;">
                                    @if($court->image)
                                        <img src="{{ asset('storage/' . $court->image) }}" class="w-100 h-100"
                                            alt="{{ $court->name }}" style="object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100"
                                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="fas fa-tennis-ball fa-4x mb-3 opacity-75"></i>
                                                <h5>{{ $court->name }}</h5>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Court Details -->
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <div class="card bg-light border-0">
                                            <div class="card-body text-center py-3">
                                                @php
                                                    $surfaceIcons = [
                                                        'clay' => ['🟤', 'Arcilla', 'warning'],
                                                        'hard' => ['🔵', 'Dura', 'primary'],
                                                        'grass' => ['🟢', 'Césped', 'success'],
                                                        'synthetic' => ['🟡', 'Sintética', 'info']
                                                    ];
                                                    $surface = $surfaceIcons[$court->surface_type] ?? ['⚪', ucfirst($court->surface_type), 'secondary'];
                                                @endphp
                                                <div class="h4 mb-1">{{ $surface[0] }}</div>
                                                <div class="font-weight-bold text-{{ $surface[2] }}">{{ $surface[1] }}</div>
                                                <small class="text-muted">Superficie</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card bg-light border-0">
                                            <div class="card-body text-center py-3">
                                                <div class="h4 mb-1 text-success">
                                                    ${{ number_format($court->price_per_hour, 0) }}</div>
                                                <div class="font-weight-bold">Por Hora</div>
                                                <small class="text-muted">Precio</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($court->description)
                                    <div class="mb-3">
                                        <h6 class="font-weight-bold">Descripción:</h6>
                                        <p class="text-muted">{{ $court->description }}</p>
                                    </div>
                                @endif

                                <!-- Features -->
                                <div class="mb-3">
                                    <h6 class="font-weight-bold">Características:</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-users text-info me-2"></i>
                                                <span>2-4 jugadores</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-clock text-primary me-2"></i>
                                                <span>Disponible 24/7</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-lightbulb text-warning me-2"></i>
                                                <span>Iluminación LED</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-shield-alt text-success me-2"></i>
                                                <span>Seguro incluido</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Reservations -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-calendar-alt me-2"></i>Reservas Próximas
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($court->reservations && $court->reservations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Fecha</th>
                                            <th>Horario</th>
                                            <th>Estado</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($court->reservations->take(5) as $reservation)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="mr-3">
                                                            <div class="icon-circle bg-primary">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="font-weight-bold">{{ $reservation->user->name }}</div>
                                                            @if(Auth::user()->isAdmin())
                                                                <div class="text-muted small">{{ $reservation->user->email }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $reservation->reservation_date->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge badge-light">
                                                        {{ $reservation->start_time }} - {{ $reservation->end_time }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusClasses = [
                                                            'pending' => 'warning',
                                                            'confirmed' => 'success',
                                                            'cancelled' => 'danger',
                                                            'completed' => 'info'
                                                        ];
                                                        $statusClass = $statusClasses[$reservation->status] ?? 'secondary';
                                                    @endphp
                                                    <span class="badge badge-{{ $statusClass }}">
                                                        {{ ucfirst($reservation->status) }}
                                                    </span>
                                                </td>
                                                <td class="font-weight-bold text-success">
                                                    ${{ number_format($reservation->total_amount, 0) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($court->reservations->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('reservations.index', ['court_id' => $court->id]) }}"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Ver Todas las Reservas
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No hay reservas próximas</h5>
                                <p class="text-muted">Esta cancha no tiene reservas programadas.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Reserve -->
                @if($court->is_active)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-bolt me-2"></i>Reserva Rápida
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mb-3">
                                    <div class="h2 text-success mb-1">${{ number_format($court->price_per_hour, 0) }}</div>
                                    <p class="text-muted">por hora</p>
                                </div>
                                <a href="{{ route('reservations.create', ['court_id' => $court->id]) }}"
                                    class="btn btn-success btn-lg w-100 mb-3">
                                    <i class="fas fa-calendar-plus me-2"></i>Reservar Ahora
                                </a>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Confirmación inmediata por email
                                </small>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Court Stats -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-bar me-2"></i>Estadísticas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="h4 font-weight-bold text-info">
                                    {{ $court->reservations_count ?? 0 }}
                                </div>
                                <small class="text-muted">Total Reservas</small>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="h4 font-weight-bold text-warning">
                                    4.8
                                </div>
                                <small class="text-muted">Calificación</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 font-weight-bold text-success">
                                    95%
                                </div>
                                <small class="text-muted">Ocupación</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 font-weight-bold text-primary">
                                    24/7
                                </div>
                                <small class="text-muted">Disponibilidad</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-phone me-2"></i>Información de Contacto
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                <span>Av. Principal 123, Ciudad</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-phone text-success me-2"></i>
                                <span>+1 234 567 8900</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <span>info@tenniscourt.com</span>
                            </div>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-map me-1"></i>Ver en Mapa
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Policies -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-file-alt me-2"></i>Políticas de la Cancha
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-1"></i>
                                Reserva mínima: 1 hora
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-1"></i>
                                Cancelación gratuita hasta 2 horas antes
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-check text-success me-1"></i>
                                Equipamiento incluido
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-times text-danger me-1"></i>
                                No se permiten mascotas
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-times text-danger me-1"></i>
                                Prohibido fumar
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge-lg {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }

        .icon-circle {
            height: 2.5rem;
            width: 2.5rem;
            border-radius: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection