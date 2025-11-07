@extends('layouts.app')

@section('title', 'Iniciar Sesión | Escuelita')

@section('content')
<style>
    /* ... (Tus estilos existentes) ... */
    .auth-wrap{
        max-width: 520px;
        margin: 0 auto;
    }
    .auth-hero{
        text-align: center;
        margin-bottom: 1.25rem;
    }
    .auth-hero .logo{
        width: 200px;
        height: 200px;
        object-fit: contain;
        border-radius: 16px;
        background: rgba(241,196,15,.12);
        border: 1px solid rgba(241,196,15,.35);
        padding: .75rem;
        box-shadow: 0 10px 24px rgba(0,0,0,.06);
    }
    .auth-hero .brand{
        margin-top: .75rem;
        font-weight: 800;
        color: var(--brand);
        letter-spacing: .5px;
    }

    /* ✨ ESTILOS PARA LOS BOTONES DE SIMULACIÓN (Adaptados de home.blade.php) ✨ */
    .icon-pill{
        width: 44px; height: 44px; border-radius: 12px;
        display:flex; align-items:center; justify-content:center;
        background: rgba(241,196,15,.18);
        border:1px solid rgba(241,196,15,.35);
        color: var(--accent);
        flex: 0 0 44px;
    }
    .emoji{ font-size: 1.25rem; line-height: 1; }
    .hover-card{ transition: transform .15s ease, box-shadow .15s ease; border:0; border-radius: 1rem; }
    .hover-card:hover{ transform: translateY(-2px); box-shadow: 0 14px 28px rgba(0,0,0,.08); }
    .sim-card .icon-pill {
        /* Ajuste de color para diferenciar los roles en la simulación */
        background: rgba(13, 110, 253, 0.1);
        border: 1px solid rgba(13, 110, 253, 0.3);
        color: #0d6efd;
    }
    .sim-card.admin-pill .icon-pill {
        background: rgba(255, 193, 7, 0.2);
        border: 1px solid rgba(255, 193, 7, 0.4);
        color: #ffc107;
    }
    .sim-card.alumno-pill .icon-pill {
        background: rgba(25, 135, 84, 0.1);
        border: 1px solid rgba(25, 135, 84, 0.3);
        color: #198754;
    }
</style>

<div class="container py-4">
    <div class="auth-wrap">

        {{-- Logo + título centrados --}}
        <div class="auth-hero">
            <img src="{{ asset('images/escuelita-logo.png') }}" alt="Escuelita" class="logo">
            <div class="brand h4 mb-0">Superusuario</div>
            <div class="text-muted">Bienvenido, inicia sesión para continuar</div>
        </div>

        <div class="card mb-4">
            <div class="card-header fw-bold">{{ __('Iniciar Sesión') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end">
                            {{ __('Correo Institucional') }}
                        </label>
                        <div class="col-md-6">
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end">
                            {{ __('Contraseña') }}
                        </label>
                        <div class="col-md-6">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Recordarme') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-8 offset-md-4 d-flex align-items-center gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Ingresar') }}
                            </button>

                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('¿Olvidaste tu contraseña?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- ---------------------------------------------------------------------- -->
        <!-- ✨ INICIO: BOTONES DE SIMULACIÓN DE VISTAS (AÑADIDOS AL LOGIN) ✨ -->
        <!-- ---------------------------------------------------------------------- -->
        <h5 class="h6 mt-4 mb-2 text-center text-muted">simula el acceso a un rol:</h5>
        
        <div class="row g-3">
            {{-- Botón Superusuario --}}
            <div class="col-12 col-md-4">
                <a href="{{ route('panel.superusuario') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 sim-card">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2 text-center p-3">
                            <div class="icon-pill">
                                <span class="emoji">&#x1F451;</span>
                            </div>
                            <small class="fw-bold text-dark">Superusuario</small>
                        </div>
                    </div>
                </a>
            </div>
            
            {{-- Botón Administrador Limitado --}}
            <div class="col-12 col-md-4">
                <a href="{{ route('panel.administrador') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 sim-card admin-pill">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2 text-center p-3">
                            <div class="icon-pill">
                                <span class="emoji">&#x1F4BC;</span>
                            </div>
                            <small class="fw-bold text-dark">Admin</small>
                        </div>
                    </div>
                </a>
            </div>
            
            {{-- Botón Alumno --}}
            <div class="col-12 col-md-4">
                <a href="{{ route('panel.alumno') }}" class="text-decoration-none">
                    <div class="card hover-card h-100 sim-card alumno-pill">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center gap-2 text-center p-3">
                            <div class="icon-pill">
                                <span class="emoji">&#x1F393;</span>
                            </div>
                            <small class="fw-bold text-dark">Alumno</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <!-- ---------------------------------------------------------------------- -->
        <!-- 🛑 FIN: BOTONES DE SIMULACIÓN 🛑 -->
        <!-- ---------------------------------------------------------------------- -->

    </div>
</div>
@endsection

