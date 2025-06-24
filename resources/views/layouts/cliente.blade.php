<?php
// resources/views/layouts/client.blade.php
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Tennis Court System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #764ba2;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--light-color);
        }

        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 10%, var(--primary-dark) 100%);
            min-height: 100vh;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
            border-radius: 10px;
            margin: 0.25rem 0.5rem;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }

        .content-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            .content-wrapper {
                margin-left: 250px;
            }
        }

        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            border-radius: 15px;
        }

        .border-left-primary {
            border-left: 0.25rem solid var(--primary-color) !important;
        }

        .border-left-success {
            border-left: 0.25rem solid var(--success-color) !important;
        }

        .border-left-info {
            border-left: 0.25rem solid var(--info-color) !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid var(--warning-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .topbar {
            height: 4.375rem;
            background-color: white;
            border-bottom: 1px solid #e3e6f0;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .topbar .nav-link {
            color: var(--secondary-color);
        }

        .dropdown-list {
            width: 20rem;
        }

        .sidebar-toggled .sidebar {
            width: 6.5rem;
        }

        .sidebar-brand-icon {
            font-size: 2rem;
        }

        .sidebar-heading {
            text-transform: uppercase;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 800;
            padding: 1.5rem 1rem 0.5rem;
        }

        .profile-dropdown {
            min-width: 200px;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
        }

        .icon-circle {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert {
            border-radius: 15px;
            border: none;
        }

        .navbar-search .form-control {
            border-radius: 25px 0 0 25px;
        }

        .navbar-search .btn {
            border-radius: 0 25px 25px 0;
        }

        /* Animaciones suaves */
        .nav-link,
        .dropdown-item,
        .btn {
            transition: all 0.3s ease;
        }

        .dropdown-menu {
            border-radius: 15px;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
    </style>

    @stack('styles')
</head>

<body id="page-top">
    <!-- Sidebar -->
    <nav class="sidebar d-none d-md-block position-fixed" style="width: 250px; z-index: 1000;">
        <div class="position-sticky pt-3">
            <!-- Sidebar Brand -->
            <a class="navbar-brand d-flex align-items-center justify-content-center py-4 text-decoration-none"
                href="{{ route('dashboard') }}">
                <div class="sidebar-brand-icon me-2">
                    <i class="fas fa-tennis-ball"></i>
                </div>
                <div class="sidebar-brand-text">Tennis Club</div>
            </a>

            <!-- Navigation -->
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }} px-4 py-3"
                        href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Dashboard
                    </a>
                </li>

                <div class="sidebar-heading">
                    Reservas
                </div>

                @can('view own reservations')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }} px-4 py-3"
                            href="{{ route('reservations.index') }}">
                            <i class="fas fa-calendar me-2"></i>
                            Mis Reservas
                        </a>
                    </li>
                @endcan

                @can('create reservations')
                    <li class="nav-item">
                        <a class="nav-link px-4 py-3" href="{{ route('reservations.create') }}">
                            <i class="fas fa-plus me-2"></i>
                            Nueva Reserva
                        </a>
                    </li>
                @endcan

                <div class="sidebar-heading">
                    Canchas
                </div>

                @can('view own courts')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('courts.*') ? 'active' : '' }} px-4 py-3"
                            href="{{ route('courts.index') }}">
                            <i class="fas fa-tennis-ball me-2"></i>
                            Ver Canchas
                        </a>
                    </li>
                @endcan

                <div class="sidebar-heading">
                    Mi Cuenta
                </div>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }} px-4 py-3"
                        href="{{ route('profile.edit') }}">
                        <i class="fas fa-user me-2"></i>
                        Mi Perfil
                    </a>
                </li>

                <!-- Solo mostrar si hay rutas de facturación configuradas -->
                @if(Route::has('billing.index'))
                    <li class="nav-item">
                        <a class="nav-link px-4 py-3" href="{{ route('billing.index') }}">
                            <i class="fas fa-credit-card me-2"></i>
                            Facturación
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Mobile Navigation -->
    <nav class="navbar navbar-expand-md navbar-dark d-md-none"
        style="background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-tennis-ball me-2"></i>
                Tennis Club
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mobileNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    @can('view own reservations')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reservations.index') }}">
                                <i class="fas fa-calendar me-2"></i>Mis Reservas
                            </a>
                        </li>
                    @endcan
                    @can('view own courts')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('courts.index') }}">
                                <i class="fas fa-tennis-ball me-2"></i>Canchas
                            </a>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="fas fa-user me-2"></i>Mi Perfil
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="content-wrapper">
        <!-- Top Navigation Bar -->
        <nav class="navbar navbar-expand navbar-light topbar static-top">
            <div class="container-fluid">
                <!-- Sidebar Toggle (Desktop) -->
                <button class="btn btn-link d-md-none" type="button" id="sidebarToggle">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Search Form -->
                <form class="d-none d-sm-inline-block form-inline me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control border-0 small" placeholder="Buscar reservas..."
                            aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell fa-fw"></i>
                            <!-- Simulación de notificaciones - reemplazar con lógica real -->
                            <span class="badge bg-danger notification-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-list shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header">
                                Notificaciones
                            </h6>
                            <!-- Ejemplo de notificaciones - reemplazar con datos reales -->
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-bell text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">Hace 2 horas</div>
                                    <span class="font-weight-bold">Reserva confirmada para mañana</span>
                                </div>
                            </a>
                            <a class="dropdown-item text-center small text-gray-500" href="#">
                                Ver todas las notificaciones
                            </a>
                        </div>
                    </li>

                    <!-- User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                            <img class="rounded-circle"
                                src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=667eea&color=fff' }}"
                                style="width: 32px; height: 32px; object-fit: cover;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <div class="dropdown-header text-center">
                                <img class="rounded-circle mb-2"
                                    src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=667eea&color=fff' }}"
                                    style="width: 50px; height: 50px; object-fit: cover;">
                                <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                Mi Perfil
                            </a>
                            @if(Route::has('billing.index'))
                                <a class="dropdown-item" href="{{ route('billing.index') }}">
                                    <i class="fas fa-credit-card fa-sm fa-fw me-2 text-gray-400"></i>
                                    Facturación
                                </a>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                Cerrar Sesión
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="container-fluid px-4 py-4">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white py-4 mt-5">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Tennis Court System {{ date('Y') }}</div>
                    <div>
                        <a href="#" class="text-decoration-none">Política de Privacidad</a>
                        &middot;
                        <a href="#" class="text-decoration-none">Términos &amp; Condiciones</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="border-bottom: 1px solid #e3e6f0;">
                    <h5 class="modal-title" id="logoutModalLabel">¿Cerrar Sesión?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Selecciona "Cerrar Sesión" si estás listo para finalizar tu sesión actual.
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e3e6f0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border-radius: 25px;">Cancelar</button>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="border-radius: 25px;">Cerrar
                            Sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function (alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-toggled');
        });

        // Smooth transitions
        document.querySelectorAll('.nav-link').forEach(function (link) {
            link.addEventListener('mouseenter', function () {
                this.style.transform = 'translateX(5px)';
            });

            link.addEventListener('mouseleave', function () {
                if (!this.classList.contains('active')) {
                    this.style.transform = 'translateX(0)';
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>