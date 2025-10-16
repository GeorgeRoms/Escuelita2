@extends('layouts.app')

@section('title', 'Iniciar Sesión | Escuelita')

@section('content')
<style>
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
</style>

<div class="container py-4">
    <div class="auth-wrap">

        {{-- Logo + título centrados --}}
        <div class="auth-hero">
            <img src="{{ asset('images/escuelita-logo.png') }}" alt="Escuelita" class="logo">
            <div class="brand h4 mb-0">Escuelita</div>
            <div class="text-muted">Bienvenido, inicia sesión para continuar</div>
        </div>

        <div class="card">
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

    </div>
</div>
@endsection

