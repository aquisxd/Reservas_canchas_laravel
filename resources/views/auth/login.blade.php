@extends('layouts.guest')

@section('title', 'Iniciar Sesión - ' . config('app.name'))

@section('styles')
<style>
    .login-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }

    .login-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        border: none;
        overflow: hidden;
    }

    .login-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .form-floating {
        position: relative;
        margin-bottom: 1rem;
    }

    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
        border-radius: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .form-floating > .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-floating > label {
        opacity: 0.65;
    }

    .btn-login {
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

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .divider {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e9ecef;
    }

    .divider span {
        background: white;
        padding: 0 1rem;
        color: #6c757d;
        font-size: 0.9rem;
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card login-card">
                    <div class="login-header">
                        <i class="fas fa-tennis-ball fa-3x mb-3"></i>
                        <h2 class="mb-2">¡Bienvenido de vuelta!</h2>
                        <p class="mb-0 opacity-75">Inicia sesión en tu cuenta</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-floating mb-3">
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="floatingEmail"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autocomplete="email"
                                       autofocus
                                       placeholder="name@example.com">
                                <label for="floatingEmail">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                </label>
                                @error('email')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="floatingPassword"
                                       name="password"
                                       required
                                       autocomplete="current-password"
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

                            <div class="form-check mb-4">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="remember"
                                       id="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-login btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                </button>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-center mb-3">
                                    <a class="text-decoration-none" href="{{ route('password.request') }}">
                                        <i class="fas fa-key me-1"></i>¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                            @endif

                            <div class="divider">
                                <span>¿No tienes cuenta?</span>
                            </div>

                            <div class="text-center">
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-user-plus me-2"></i>Crear Cuenta Nueva
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
