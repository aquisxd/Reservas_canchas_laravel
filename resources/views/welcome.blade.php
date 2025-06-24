<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tennis Court System') }} - Reserva tu cancha</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="800" cy="300" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="400" cy="600" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="900" cy="700" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="100" cy="800" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
            opacity: 0.5;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
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

        .stats-section {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #667eea;
            display: block;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .btn-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            color: white;
        }

        .btn-outline-gradient {
            border: 2px solid #667eea;
            color: #667eea;
            background: transparent;
            padding: 13px 30px;
            border-radius: 50px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .courts-preview {
            padding: 80px 0;
        }

        .court-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .court-card:hover {
            transform: translateY(-5px);
        }

        .court-image {
            height: 200px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .footer-section {
            background: #2c3e50;
            color: white;
            padding: 60px 0 30px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 10px;
            transition: background 0.3s ease;
        }

        .social-links a:hover {
            background: #667eea;
            color: white;
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

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-tennis-ball text-primary me-2"></i>
                {{ config('app.name', 'Tennis Court') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#courts">Canchas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contacto</a>
                    </li>
                    @guest
                        <li class="nav-item ms-3">
                            <a class="btn btn-outline-gradient me-2" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Entrar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-gradient" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Registrarse
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <div class="user-info">
                                <span class="user-name">{{ auth()->user()->name }}</span>
                                <a class="btn btn-gradient me-2" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                </a>
                                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-logout">
                                        <i class="fas fa-sign-out-alt me-1"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </li>

                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold text-white mb-4">
                            Reserva tu <span class="text-warning">Cancha de Tenis</span>
                            <br>de forma fácil y rápida
                        </h1>
                        <p class="lead text-white-50 mb-5">
                            Sistema moderno de reservas online. Encuentra la cancha perfecta,
                            reserva tu horario y disfruta del mejor tenis.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-gradient btn-lg pulse">
                                    <i class="fas fa-rocket me-2"></i>Comenzar Ahora
                                </a>
                                <a href="#features" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-play me-2"></i>Ver Características
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="btn btn-gradient btn-lg pulse">
                                    <i class="fas fa-tachometer-alt me-2"></i>Ir al Dashboard
                                </a>
                                <a href="{{ route('courts.index') }}" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-map-marker-alt me-2"></i>Ver Canchas
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="text-center">
                        <div class="floating">
                            <i class="fas fa-tennis-ball" style="font-size: 15rem; color: rgba(255,255,255,0.1);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-card">
                        <span class="stat-number" data-count="50">0</span>
                        <span class="stat-label">Canchas Disponibles</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-card">
                        <span class="stat-number" data-count="1000">0</span>
                        <span class="stat-label">Reservas Realizadas</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-card">
                        <span class="stat-number" data-count="500">0</span>
                        <span class="stat-label">Clientes Satisfechos</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-card">
                        <span class="stat-number" data-count="24">0</span>
                        <span class="stat-label">Horas de Servicio</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-4">¿Por qué elegirnos?</h2>
                    <p class="lead text-muted">
                        Ofrecemos la mejor experiencia en reservas de canchas de tenis con
                        tecnología moderna y un servicio excepcional.
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card text-center">
                        <div class="feature-icon icon-primary">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Reserva 24/7</h4>
                        <p class="text-muted">
                            Sistema disponible las 24 horas del día, los 7 días de la semana.
                            Reserva cuando quieras.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card text-center">
                        <div class="feature-icon icon-success">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Fácil de Usar</h4>
                        <p class="text-muted">
                            Interfaz intuitiva y responsive. Funciona perfecto en móviles,
                            tablets y computadoras.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card text-center">
                        <div class="feature-icon icon-warning">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Seguro y Confiable</h4>
                        <p class="text-muted">
                            Tus datos están protegidos con la mejor tecnología de seguridad.
                            Pagos seguros garantizados.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card text-center">
                        <div class="feature-icon icon-info">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Soporte 24/7</h4>
                        <p class="text-muted">
                            Equipo de soporte disponible para ayudarte en cualquier momento.
                            ¡Estamos aquí para ti!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courts Preview Section -->
    <section id="courts" class="courts-preview bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-4">Nuestras Canchas</h2>
                    <p class="lead text-muted">
                        Canchas de primera calidad con diferentes superficies para
                        satisfacer todos los estilos de juego.
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="court-card">
                        <div class="court-image">
                            <i class="fas fa-tennis-ball"></i>
                        </div>
                        <div class="p-4">
                            <h5 class="fw-bold mb-2">Cancha Central</h5>
                            <p class="text-muted mb-3">Superficie dura profesional</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-primary">Disponible</span>
                                <span class="fw-bold text-success">$50/hora</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="court-card">
                        <div class="court-image">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="p-4">
                            <h5 class="fw-bold mb-2">Cancha Norte</h5>
                            <p class="text-muted mb-3">Superficie de arcilla</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-success">Popular</span>
                                <span class="fw-bold text-success">$35/hora</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="court-card">
                        <div class="court-image">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="p-4">
                            <h5 class="fw-bold mb-2">Cancha Sur</h5>
                            <p class="text-muted mb-3">Superficie sintética</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info">Nuevo</span>
                                <span class="fw-bold text-success">$30/hora</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                @guest
                    <a href="{{ route('register') }}" class="btn btn-gradient btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Reservar Ahora
                    </a>
                @else
                    <a href="{{ route('courts.index') }}" class="btn btn-gradient btn-lg">
                        <i class="fas fa-eye me-2"></i>Ver Todas las Canchas
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center" data-aos="zoom-in">
                    <h2 class="display-5 fw-bold text-white mb-4">
                        ¿Listo para jugar?
                    </h2>
                    <p class="lead text-white-50 mb-5">
                        Únete a miles de jugadores que ya confían en nuestro sistema.
                        ¡Tu cancha perfecta te está esperando!
                    </p>
                    @guest
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Crear Cuenta Gratis
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Ya tengo cuenta
                            </a>
                        </div>
                    @else
                        <a href="{{ route('reservations.create') }}" class="btn btn-light btn-lg">
                            <i class="fas fa-calendar-plus me-2"></i>Hacer Reserva
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact" class="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-4">
                        <i class="fas fa-tennis-ball me-2"></i>
                        {{ config('app.name', 'Tennis Court') }}
                    </h5>
                    <p class="text-light">
                        El mejor sistema de reservas de canchas de tenis.
                        Tecnología moderna para una experiencia excepcional.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Enlaces</h6>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-light text-decoration-none">Inicio</a></li>
                        <li><a href="#features" class="text-light text-decoration-none">Características</a></li>
                        <li><a href="#courts" class="text-light text-decoration-none">Canchas</a></li>
                        <li><a href="#contact" class="text-light text-decoration-none">Contacto</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Servicios</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Reserva Online</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Gestión de Canchas</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Soporte 24/7</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Aplicación Móvil</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 mb-4">
                    <h6 class="fw-bold mb-3">Contacto</h6>
                    <ul class="list-unstyled">
                        <li class="text-light mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            Av. Principal 123, Ciudad
                        </li>
                        <li class="text-light mb-2">
                            <i class="fas fa-phone me-2"></i>
                            +1 234 567 8900
                        </li>
                        <li class="text-light mb-2">
                            <i class="fas fa-envelope me-2"></i>
                            info@tenniscourt.com
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light mb-0">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Tennis Court System') }}.
                        Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-light text-decoration-none me-3">Términos de Uso</a>
                    <a href="#" class="text-light text-decoration-none">Política de Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true
        });

        // Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.stat-number');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-count'));
                const increment = target / 200;
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        counter.textContent = target + (target === 24 ? '' : '+');
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 10);
            });
        }

        // Trigger counter animation when stats section is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        });

        observer.observe(document.querySelector('.stats-section'));

        // Smooth scrolling for anchor links
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

        // Change navbar background on scroll
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
</body>

</html>