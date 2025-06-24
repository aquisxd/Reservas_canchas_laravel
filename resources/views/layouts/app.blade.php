<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Tennis Court System'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

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

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light-color);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0 0.2rem;
            padding: 0.5rem 1rem !important;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            padding: 0.7rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 25px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .alert {
            border: none;
            border-radius: 15px;
            font-weight: 500;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(45deg, #d4edda, #c3e6cb);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(45deg, #f8d7da, #f5c6cb);
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(45deg, #fff3cd, #ffeaa7);
            color: #856404;
        }

        .alert-info {
            background: linear-gradient(45deg, #d1ecf1, #bee5eb);
            color: #0c5460;
        }

        .dropdown-menu {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
        }

        .dropdown-item {
            font-weight: 500;
            padding: 0.7rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            color: white;
            transform: translateX(5px);
        }

        .badge {
            border-radius: 10px;
            font-weight: 600;
        }

        .badge-admin {
            background: linear-gradient(45deg, #f093fb, #f5576c);
            color: white;
        }

        .badge-owner {
            background: linear-gradient(45deg, #4facfe, #00f2fe);
            color: white;
        }

        .badge-client {
            background: linear-gradient(45deg, #43e97b, #38f9d7);
            color: white;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
        }

        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                margin: 0.2rem 0;
            }

            .card {
                margin-bottom: 1rem;
            }
        }

        /* Navigation active states */
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(45deg, var(--primary-color), var(--primary-dark));
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>

    @stack('styles')
</head>

<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <i class="fas fa-tennis-ball me-2"></i>
                    {{ config('app.name', 'Tennis Court System') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                    href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                            </li>

                            @can('view own courts')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('courts.*') ? 'active' : '' }}"
                                        href="{{ route('courts.index') }}">
                                        <i class="fas fa-tennis-ball me-1"></i>Canchas
                                    </a>
                                </li>
                            @endcan

                            @can('view own reservations')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}"
                                        href="{{ route('reservations.index') }}">
                                        <i class="fas fa-calendar me-1"></i>Reservas
                                    </a>
                                </li>
                            @endcan

                            @auth
                                @if(auth()->user()->hasRole('admin'))
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"
                                            href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-shield-alt me-1"></i>Administración
                                        </a>
                                    </li>
                                @endif
                            @endauth

                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                                </a>
                            </li>
                            <li class="nav-item ms-2">
                                <a class="btn btn-outline-light btn-sm" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>Registrarse
                                </a>
                            </li>
                        @else
                            <!-- Notifications (if implemented) -->
                            <li class="nav-item dropdown me-3">
                                <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-bell"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="font-size: 0.6rem;">
                                        3
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                                    <li>
                                        <h6 class="dropdown-header">
                                            <i class="fas fa-bell me-2"></i>Notificaciones
                                        </h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <small class="text-muted">Hace 2 horas</small><br>
                                            Tu reserva ha sido confirmada
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-center small" href="#">
                                            Ver todas las notificaciones
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- User Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=ffffff&color=667eea' }}"
                                        alt="Avatar" class="user-avatar me-2">
                                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                    @if(Auth::user()->hasAnyRole(['super_admin', 'admin']))
                                        <span class="badge badge-admin ms-2">Admin</span>
                                    @elseif(Auth::user()->hasRole('court_owner'))
                                        <span class="badge badge-owner ms-2">Propietario</span>
                                    @else
                                        <span class="badge badge-client ms-2">Cliente</span>
                                    @endif
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <div class="dropdown-header text-center">
                                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=ffffff&color=667eea' }}"
                                                alt="Avatar" class="rounded-circle mb-2" style="width: 50px; height: 50px;">
                                            <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                            <small class="text-muted">{{ Auth::user()->email }}</small>
                                        </div>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user me-2"></i>Mi Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    @can('view admin dashboard')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-shield-alt me-2"></i>Panel Admin
                                            </a>
                                        </li>
                                    @endcan
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                        </a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-4">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show fade-in-up" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show fade-in-up" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="container">
                    <div class="alert alert-warning alert-dismissible fade show fade-in-up" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="container">
                    <div class="alert alert-info alert-dismissible fade show fade-in-up" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-light py-5 mt-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="mb-0 text-muted">
                            &copy; {{ date('Y') }} {{ config('app.name', 'Tennis Court System') }}.
                            Todos los derechos reservados.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <a href="#" class="text-decoration-none me-3">Términos de Uso</a>
                        <a href="#" class="text-decoration-none me-3">Política de Privacidad</a>
                        <a href="#" class="text-decoration-none">Soporte</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Enhanced navbar collapse behavior
        document.addEventListener('click', function (e) {
            const navbar = document.querySelector('.navbar-collapse');
            const toggleButton = document.querySelector('.navbar-toggler');

            if (navbar && navbar.classList.contains('show') &&
                !navbar.contains(e.target) && !toggleButton.contains(e.target)) {
                const bsCollapse = new bootstrap.Collapse(navbar, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>