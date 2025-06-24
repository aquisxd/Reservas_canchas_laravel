@extends('layouts.app')

@section('content')
    <style>
        .admin-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: calc(100vh - 100px);
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .admin-dashboard::before {
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

        .admin-header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .admin-title {
            color: white;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .admin-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.3rem;
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
            height: 5px;
        }

        .stats-card.users::before {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .stats-card.courts::before {
            background: linear-gradient(45deg, #11998e, #38ef7d);
        }

        .stats-card.reservations::before {
            background: linear-gradient(45deg, #f093fb, #f5576c);
        }

        .stats-card.revenue::before {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .stats-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: white;
            float: right;
        }

        .icon-users {
            background: linear-gradient(45deg, #667eea, #764ba2);
        }

        .icon-courts {
            background: linear-gradient(45deg, #11998e, #38ef7d);
        }

        .icon-reservations {
            background: linear-gradient(45deg, #f093fb, #f5576c);
        }

        .icon-revenue {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 0.5rem;
            display: block;
            clear: left;
        }

        .stats-label {
            color: #666;
            font-weight: 500;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .section-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: none;
            height: 100%;
        }

        .section-title {
            color: #333;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .section-title i {
            color: #667eea;
            font-size: 1.4rem;
        }

        .reservation-item {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .reservation-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: right 0.5s;
        }

        .reservation-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .reservation-item:hover::before {
            right: 100%;
        }

        .action-btn {
            background: white;
            border: 2px solid;
            border-radius: 15px;
            padding: 1.2rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            overflow: hidden;
        }

        .action-btn.users {
            border-color: #667eea;
            color: #667eea;
        }

        .action-btn.courts {
            border-color: #11998e;
            color: #11998e;
        }

        .action-btn.reservations {
            border-color: #f093fb;
            color: #f093fb;
        }

        .action-btn.reports {
            border-color: #4facfe;
            color: #4facfe;
        }

        .action-btn.roles {
            border-color: #ff6b6b;
            color: #ff6b6b;
        }

        .action-btn:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .action-btn.users:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-color: #667eea;
        }

        .action-btn.courts:hover {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            border-color: #11998e;
        }

        .action-btn.reservations:hover {
            background: linear-gradient(45deg, #f093fb, #f5576c);
            border-color: #f093fb;
        }

        .action-btn.reports:hover {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            border-color: #4facfe;
        }

        .action-btn.roles:hover {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border-color: #ff6b6b;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 25px;
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

        .status-cancelled {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
        }

        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin: 0.5rem 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 10px;
            transition: width 1s ease;
        }

        .metric-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .metric-item:last-child {
            border-bottom: none;
        }

        .metric-label {
            font-weight: 500;
            color: #666;
        }

        .metric-value {
            font-size: 1.3rem;
            font-weight: 700;
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
                transform: translateY(-15px);
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

        @media (max-width: 768px) {
            .admin-title {
                font-size: 2.2rem;
            }

            .admin-dashboard {
                padding: 1rem 0;
            }

            .stats-card,
            .section-card {
                margin-bottom: 1rem;
            }
        }
    </style>

    <div class="admin-dashboard">
        <div class="container dashboard-content">
            <!-- Header del Dashboard -->
            <div class="admin-header">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="admin-title">
                            <i class="fas fa-shield-alt me-3"></i>
                            Panel de Administración
                        </h1>
                        <p class="admin-subtitle">
                            Control total del sistema de reservas de canchas
                        </p>
                    </div>
                    <div class="col-lg-4 text-end">
                        <div class="floating">
                            <i class="fas fa-chart-line" style="font-size: 5rem; color: rgba(255,255,255,0.3);"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjetas de estadísticas -->
            @if(isset($stats))
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card users">
                            <div class="stats-icon icon-users">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="stats-number">{{ $stats['total_users'] ?? 0 }}</span>
                            <p class="stats-label">Total Usuarios</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card courts">
                            <div class="stats-icon icon-courts">
                                <i class="fas fa-tennis-ball"></i>
                            </div>
                            <span class="stats-number">{{ $stats['total_courts'] ?? 0 }}</span>
                            <p class="stats-label">Total Canchas</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card reservations">
                            <div class="stats-icon icon-reservations">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <span class="stats-number">{{ $stats['today_reservations'] ?? 0 }}</span>
                            <p class="stats-label">Reservas Hoy</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stats-card revenue">
                            <div class="stats-icon icon-revenue">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <span class="stats-number">${{ number_format($stats['monthly_revenue'] ?? 0, 0) }}</span>
                            <p class="stats-label">Ingresos del Mes</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grid de contenido principal -->
            <div class="row">
                <!-- Reservas Pendientes -->
                <div class="col-lg-8 mb-4">
                    <div class="section-card">
                        <h3 class="section-title">
                            <i class="fas fa-clock"></i>
                            Reservas Pendientes de Aprobación
                        </h3>

                        @if(isset($pendingReservations) && $pendingReservations->count() > 0)
                            @foreach($pendingReservations as $reservation)
                                <div class="reservation-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="mb-1">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                {{ $reservation->user->name ?? 'Usuario' }}
                                            </h5>
                                            <p class="mb-1">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                {{ $reservation->court->name ?? 'Cancha' }}
                                            </p>
                                            <p class="mb-0 text-muted">
                                                <i class="fas fa-calendar me-2"></i>
                                                {{ \Carbon\Carbon::parse($reservation->date)->format('d/m/Y') }}
                                                <i class="fas fa-clock ms-3 me-2"></i>
                                                {{ $reservation->start_time }} - {{ $reservation->end_time }}
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="status-badge status-pending">
                                                Pendiente
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="text-center mt-3">
                                <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Ver Todas las Reservas
                                </a>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                                <h5>¡Todo al día!</h5>
                                <p class="text-muted">No hay reservas pendientes de aprobación</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="col-lg-4 mb-4">
                    <div class="section-card">
                        <h3 class="section-title">
                            <i class="fas fa-bolt"></i>
                            Acciones Rápidas
                        </h3>

                        <div class="d-grid gap-3">
                            @can('view users')
                                <a href="{{ route('admin.users.index') }}" class="action-btn users">
                                    <i class="fas fa-users"></i>
                                    <div>
                                        <div class="fw-bold">Gestionar Usuarios</div>
                                        <small>Administrar cuentas y perfiles</small>
                                    </div>
                                </a>
                            @endcan

                            @can('view all courts')
                                <a href="{{ route('admin.courts.index') }}" class="action-btn courts">
                                    <i class="fas fa-tennis-ball"></i>
                                    <div>
                                        <div class="fw-bold">Gestionar Canchas</div>
                                        <small>Configurar canchas y horarios</small>
                                    </div>
                                </a>
                            @endcan

                            @can('view all reservations')
                                <a href="{{ route('admin.reservations.index') }}" class="action-btn reservations">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <div class="fw-bold">Gestionar Reservas</div>
                                        <small>Ver y modificar reservas</small>
                                    </div>
                                </a>
                            @endcan

                            @can('view reports')
                                <a href="{{ route('admin.reports') }}" class="action-btn reports">
                                    <i class="fas fa-chart-bar"></i>
                                    <div>
                                        <div class="fw-bold">Ver Reportes</div>
                                        <small>Estadísticas y análisis</small>
                                    </div>
                                </a>
                            @endcan

                            @role('super_admin')
                            <a href="{{ route('admin.roles.index') }}" class="action-btn roles">
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <div class="fw-bold">Roles y Permisos</div>
                                    <small>Configurar accesos</small>
                                </div>
                            </a>
                            @endrole
                        </div>
                    </div>
                </div>
            </div>

            <!-- Métricas Adicionales -->
            <div class="row">
                <!-- Usuarios por Rol -->
                <div class="col-lg-6 mb-4">
                    <div class="chart-container">
                        <h3 class="section-title">
                            <i class="fas fa-users-cog"></i>
                            Distribución de Usuarios
                        </h3>

                        @if(isset($usersByRole) && count($usersByRole) > 0)
                            @php $maxUsers = max($usersByRole) @endphp
                            @foreach($usersByRole as $role => $count)
                                <div class="metric-item">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="metric-label">{{ ucfirst(str_replace('_', ' ', $role)) }}</span>
                                            <span class="metric-value">{{ $count }}</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-fill"
                                                style="width: {{ $maxUsers > 0 ? ($count / $maxUsers) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted">No hay datos disponibles</p>
                        @endif
                    </div>
                </div>

                <!-- Estado del Sistema -->
                <div class="col-lg-6 mb-4">
                    <div class="chart-container">
                        <h3 class="section-title">
                            <i class="fas fa-server"></i>
                            Estado del Sistema
                        </h3>

                        <div class="metric-item">
                            <span class="metric-label">Canchas Activas</span>
                            <span class="metric-value text-success">{{ $stats['active_courts'] ?? 0 }}</span>
                        </div>

                        <div class="metric-item">
                            <span class="metric-label">Reservas Pendientes</span>
                            <span class="metric-value text-warning">{{ $stats['pending_reservations'] ?? 0 }}</span>
                        </div>

                        <div class="metric-item">
                            <span class="metric-label">Nuevos Usuarios (Este Mes)</span>
                            <span class="metric-value text-primary">{{ $stats['new_users_this_month'] ?? 0 }}</span>
                        </div>

                        <div class="metric-item">
                            <span class="metric-label">Total de Reservas</span>
                            <span class="metric-value text-info">{{ $stats['total_reservations'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas del Sistema -->
            @if(isset($alerts) && count($alerts) > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="section-card">
                            <h3 class="section-title">
                                <i class="fas fa-exclamation-triangle"></i>
                                Alertas del Sistema
                            </h3>

                            @foreach($alerts as $alert)
                                <div class="alert alert-{{ $alert['type'] }} d-flex align-items-center" role="alert">
                                    <i class="fas fa-info-circle me-3"></i>
                                    <div class="flex-grow-1">
                                        <strong>{{ $alert['title'] }}</strong><br>
                                        {{ $alert['message'] }}
                                    </div>
                                    @if(isset($alert['action']))
                                        <a href="{{ $alert['action']['url'] }}" class="btn btn-sm btn-outline-{{ $alert['type'] }}">
                                            {{ $alert['action']['text'] }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
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
                }, 30);
            });

            // Animación de barras de progreso
            setTimeout(() => {
                const progressBars = document.querySelectorAll('.progress-fill');
                progressBars.forEach(bar => {
                    bar.style.width = bar.style.width;
                });
            }, 500);
        });
    </script>
@endsection