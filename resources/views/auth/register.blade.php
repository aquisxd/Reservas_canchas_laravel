<!-- resources/views/auth/register.blade.php -->
@extends('layouts.guest')

@section('title', 'Crear Cuenta - ' . config('app.name'))

@section('styles')
    <style>
        .register-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            padding: 2rem 0;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .form-floating>.form-control {
            height: calc(3.5rem + 2px);
            line-height: 1.25;
            border-radius: 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-floating>.form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-register {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="register-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card register-card">
                        <div class="register-header">
                            <i class="fas fa-user-plus fa-3x mb-3"></i>
                            <h2 class="mb-2">Crear Cuenta</h2>
                            <p class="mb-0 opacity-75">Únete a nuestro sistema de reservas</p>
                        </div>

                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="floatingName" name="name" value="{{ old('name') }}" required
                                                autocomplete="name" autofocus placeholder="Nombre completo">
                                            <label for="floatingName">
                                                <i class="fas fa-user me-2"></i>Nombre Completo
                                            </label>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                id="floatingPhone" name="phone" value="{{ old('phone') }}"
                                                placeholder="Teléfono">
                                            <label for="floatingPhone">
                                                <i class="fas fa-phone me-2"></i>Teléfono (Opcional)
                                            </label>
                                            @error('phone')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="floatingEmail" name="email" value="{{ old('email') }}" required
                                        autocomplete="email" placeholder="name@example.com">
                                    <label for="floatingEmail">
                                        <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                    </label>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="floatingPassword" name="password" required autocomplete="new-password"
                                                placeholder="Password">
                                            <label for="floatingPassword">
                                                <i class="fas fa-lock me-2"></i>Contraseña
                                            </label>
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input type="password" class="form-control" id="floatingPasswordConfirm"
                                                name="password_confirmation" required autocomplete="new-password"
                                                placeholder="Confirm Password">
                                            <label for="floatingPasswordConfirm">
                                                <i class="fas fa-lock me-2"></i>Confirmar Contraseña
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a>
                                    </label>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-register btn-lg">
                                        <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                                    </button>
                                </div>

                                <div class="divider">
                                    <span>¿Ya tienes cuenta?</span>
                                </div>

                                <div class="text-center">
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100">
                                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection