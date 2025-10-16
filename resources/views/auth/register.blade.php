@extends('layouts.app')

@section('title', 'Registro de Usuario | Escuelita')

@section('content')
<style>
    /* Estilos copiados de login.blade.php para el logo central */
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

        {{-- Logo + título centrados (Estilo copiado de Login) --}}
        <div class="auth-hero">
            <img src="{{ asset('images/escuelita-logo.png') }}" alt="Escuelita" class="logo">
            <div class="brand h4 mb-0">Escuelita</div>
            <div class="text-muted">Crea una cuenta para acceder al sistema.</div>
        </div>

        <div class="card">
            {{-- Título en español --}}
            <div class="card-header fw-bold">{{ __('Registro de Usuario') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Campo Nombre --}}
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Nombre Completo') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo Correo Electrónico (Institucional) --}}
                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Correo Institucional') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo Contraseña --}}
                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Contraseña') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo Confirmar Contraseña --}}
                    <div class="mb-3 row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirmar Contraseña') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    {{-- Botón de Registro --}}
                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Registrar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection