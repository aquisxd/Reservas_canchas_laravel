@extends('layouts.app')

@section('content')
    <style>
        .dashboard-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: calc(100vh - 100px);
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .dashboard-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="800" cy="300" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="400" cy="600" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="900" cy="700" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="100" cy="800" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            opacity: 0.3;
        }

        .dashboard-content {
            position: relative;
            z-index: 2;
        }

        .welcome-section {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .welcome-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .welcome-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            margin-bottom: 0;
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.8rem;
            color: white;
        }

        .icon-primary {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .icon-success {
            background: linear-gradient(45deg, #11998e, #38ef7d);
        }

        .icon-warning {
            background: linear-gradient(45deg, #f093fb, #f5576c);
        }

        .icon-info {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            display: block;
        }

        .stats-label {
            color: #666;
            font-weight: 500;
            font-size: 1rem;
            margin-bottom: 0;
        }

        .section-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: none;
        }

        .section-title {
            color: #333;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title i {
            color: #667eea;
            font-size: 1.3rem;
        }

        .reservation-item {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .reservation-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .court-favorite {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .court-favorite:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
            text-decoration: none;
        }

        .btn-outline-gradient {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
            }
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-confirmed {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            color: white;
        }

        .status-pending {
            background: linear-gradient(45deg, #f093fb, #f5576c);
            color: white;
        }

        .status-completed {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        @media (max-width: 768px) {
            .welcome-title {
                font-size: 2rem;
            }

            .stats-card {
                margin-bottom: 1rem;
            }

            .dashboard-container {
                padding: 1rem 0;
            }
        }
    </style>

    <div class="dashboard-container">
        <div class="container dashboard-content">
            <!-- Welcome Section -->
            <div class="welcome-section">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="welcome-title">¡Bienvenido, {{ auth()->user()->name }}! 🎾</h1>
                        <p class="welcome-subtitle">
                            Gestiona tus reservas y descubre las mejores canchas de tenis
                        </p>
                    </div>
                    <div class="col-lg-4 text-end">
                        <div class="floating">
                            <i class="fas fa-tennis-ball" style="font-size: 5rem; color: rgba(255,255,255,0.3);"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            @if(isset($stats))
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon icon-primary">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <span class="stats-number">{{ $stats['total_reservations'] ?? 0 }}</span>
                            <p class="stats-label">Total de Reservas</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon icon-success">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span class="stats-number">{{ $stats['upcoming_reservations'] ?? 0 }}</span>
                            <p class="stats-label">Próximas Reservas</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon icon-info">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <span class="stats-number">{{ $stats['completed_reservations'] ?? 0 }}</span>
                            <p class="stats-label">Partidos Jugados</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card">
                            <div class="stats-icon icon-warning">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <span class="stats-number">${{ number_format($stats['total_spent'] ?? 0, 0) }}</span>
                            <p class="stats-label">Total Invertido</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <!-- Próximas Reservas -->
                <div class="col-lg-8 mb-4">
                    <div class="section-card">
                        <h3 class="section-title">
                            <i class="fas fa-calendar-alt"></i>
                            Próximas Reservas
                        </h3>

                        @if(isset($upcomingReservations) && $upcomingReservations->count() > 0)
                            @foreach($upcomingReservations as $reservation)
                                <div class="reservation-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="mb-1">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                {{ $reservation->court->name ?? 'Cancha' }}
                                            </h5>
                                            <p class="mb-1">
                                                <i class="fas fa-calendar me-2"></i>
                                                {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}
                                                <i class="fas fa-clock ms-3 me-2"></i>
                                                {{ $reservation->start_time }} - {{ $reservation->end_time }}
                                            </p>
                                            <p class="mb-0 text-muted">
                                                <i class="fas fa-dollar-sign me-2"></i>
                                                ${{ number_format($reservation->total_price ?? 0, 0) }}
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge-status status-{{ strtolower($reservation->status) }}">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="text-center mt-3">
                                <a href="{{ route('reservations.index') }}" class="btn-outline-gradient">
                                    <i class="fas fa-eye"></i>
                                    Ver Todas las Reservas
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h5>No tienes reservas próximas</h5>
                                <p>¡Es hora de reservar tu próximo partido!</p>
                                <a href="{{ route('reservations.create') }}" class="btn-gradient pulse">
                                    <i class="fas fa-plus"></i>
                                    Hacer Reserva
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Acciones Rápidas -->
                    <div class="section-card mb-4">
                        <h3 class="section-title">
                            <i class="fas fa-bolt"></i>
                            Acciones Rápidas
                        </h3>

                        <div class="d-grid gap-3">
                            @can('create reservations')
                                <a href="{{ route('reservations.create') }}" class="btn-gradient">
                                    <i class="fas fa-calendar-plus"></i>
                                    Nueva Reserva
                                </a>
                            @endcan

                            @can('view own reservations')
                                <a href="{{ route('reservations.index') }}" class="btn-outline-gradient">
                                    <i class="fas fa-list"></i>
                                    Mis Reservas
                                </a>
                            @endcan

                            <a href="{{ route('profile.edit') }}" class="btn-outline-gradient">
                                <i class="fas fa-user-edit"></i>
                                Editar Perfil
                            </a>
                        </div>
                    </div>

                    <!-- Canchas Favoritas -->
                    @if(isset($favoriteCourts) && $favoriteCourts->count() > 0)
                        <div class="section-card">
                            <h3 class="section-title">
                                <i class="fas fa-heart"></i>
                                Tus Canchas Favoritas
                            </h3>

                            @foreach($favoriteCourts as $court)
                                <div class="court-favorite">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $court->name }}</h6>
                                            <small class="opacity-75">
                                                <i class="fas fa-tennis-ball me-1"></i>
                                                {{ $court->reservations_count }} reservas
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Actividad Reciente -->
                    @if(isset($recentReservations) && $recentReservations->count() > 0)
                        <div class="section-card">
                            <h3 class="section-title">
                                <i class="fas fa-history"></i>
                                Actividad Reciente
                            </h3>

                            @foreach($recentReservations->take(3) as $reservation)
                                <div class="reservation-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $reservation->court->name ?? 'Cancha' }}</h6>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($reservation->created_at)->diffForHumans() }}
                                            </small>
                                        </div>
                                        <span class="badge-status status-{{ strtolower($reservation->status) }}">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animación de contadores
        document.addEventListener('DOMContentLoaded', function () {
            const counters = document.querySelectorAll('.stats-number');

            counters.forEach(counter => {
                const target = parseInt(counter.textContent.replace(/[^0-9]/g, '')) || 0;
                const increment = target / 100;
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = counter.textContent.includes('$') ?
                            '$' + target.toLocaleString() : target;
                        clearInterval(timer);
                    } else {
                        const displayValue = Math.floor(current);
                        counter.textContent = counter.textContent.includes('$') ?
                            '$' + displayValue.toLocaleString() : displayValue;
                    }
                }, 20);
            });
        });
    </script>
@endsection