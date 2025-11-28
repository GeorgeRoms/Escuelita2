@extends('layouts.app')
@section('title','Escuelita | Inicio docente')

@section('nav_links')
    {{-- Enlaces del navbar para el profesor --}}
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home.profesor') }}">Inicio</a>
    </li>
@endsection

@php
    /** @var \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable $user */
    $prof = $profesor ?? Auth::user();

    // Iniciales (2 letras)
    $nombreBase = trim(($prof->nombre ?? $prof->name ?? 'Profesor') . ' ' . ($prof->apellido_pat ?? ''));
    $partes = explode(' ', $nombreBase);
    $initials = strtoupper(
        mb_substr($partes[0] ?? 'P', 0, 1) .
        mb_substr($partes[1] ?? '', 0, 1)
    );

    // Nombre completo y nombre corto
    $nombreCompleto = trim(
        ($prof->nombre ?? $prof->name ?? '') . ' ' .
        ($prof->apellido_pat ?? '') . ' ' .
        ($prof->apellido_mat ?? '')
    );
    $nombreCompleto = $nombreCompleto ?: ($prof->name ?? 'Profesor');
    $nombreCorto = strtok($nombreCompleto, ' ') ?: 'Profesor';

    // Menú principal del profe
    $menu_items = [
    [
        'icon'  => 'bi-people',
        'title' => 'Cursos vigentes',
        'text'  => 'Consulta tus grupos actuales y captura calificaciones.',
        'route' => route('profesor.cursos.vigentes'),
    ],
    [
        'icon'  => 'bi-journal-text',
        'title' => 'Historial de cursos',
        'text'  => 'Revisa los cursos que has impartido en otros periodos.',
        'route' => route('profesor.cursos.historial'),
    ],
    [
        'icon'  => 'bi-person-lines-fill',
        'title' => 'Datos personales',
        'text'  => 'Consulta tu información de contacto registrada.',
        'route' => route('profesor.datos_personales'),
    ],
];


@endphp

@section('content')

@if (session('error'))
    <div class="alert alert-danger mt-2">
        {{ session('error') }}
    </div>
@endif

<style>
    /* Estilos base del hero */
    .home-hero{
        background: linear-gradient(16deg, #184477 0%, #184477 60%);
        color:#fff;
        border-radius: 1.25rem;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.62);
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
    }

    .avatar-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem auto;
        box-shadow: 0 4px 12px rgba(0,0,0,.18);
        font-size: 2.4rem;
        font-weight: 600;
    }

    .hero-title {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: .25rem;
    }

    .hero-subtitle {
        font-size: .95rem;
        opacity: .9;
    }

    .side-card {
        background: #ffffff;
        border-radius: 1rem;
        border: 0;
        box-shadow: 0 10px 18px rgba(0,0,0,0.08);
    }

    .side-card-header {
        border-bottom: 0;
        background: transparent;
        padding-bottom: .35rem;
    }

    .menu-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .9rem 1rem;
        margin-bottom: .6rem;
        border-radius: .9rem;
        text-decoration: none;
        background: #f9fafb;
        color: #111827;
        transition: background .15s ease, transform .15s ease, box-shadow .15s ease;
    }

    .menu-link:hover{
        background:#f3f4ff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0,0,0,.08);
        text-decoration:none;
        color:#111827;
    }

    .menu-icon-wrapper{
        width: 42px;
        height: 42px;
        border-radius: 999px;
        background: rgba(241,196,15,.18);
        border:1px solid rgba(241,196,15,.35);
        display:flex;
        align-items:center;
        justify-content:center;
        margin-right: .8rem;
        flex: 0 0 42px;
    }

    .menu-icon-wrapper i{
        font-size: 1.25rem;
        color:#184477;
    }

    .menu-text-title{
        font-weight: 600;
        margin-bottom: .05rem;
    }

    .menu-text-desc{
        font-size: .85rem;
        color:#6b7280;
        margin:0;
    }

    .menu-arrow{
        color:#9ca3af;
        font-size:1.1rem;
        margin-left: .75rem;
    }
</style>

<div class="home-hero">
    <div class="row justify-content-center">
        {{-- Encabezado --}}
        <div class="col-12 text-center text-white mb-4">
            <div class="avatar-circle">
                {{ $initials }}
            </div>
            <h4 class="hero-title mb-1">¡Hola, {{ $nombreCorto }}!</h4>
            <p class="hero-subtitle mb-0">
                Bienvenido a tu panel docente. Aquí podrás consultar tus grupos,
                capturar calificaciones y revisar tu historial de cursos.
            </p>
        </div>

        {{-- Menú principal --}}
        <div class="col-12 col-lg-10">
            <div class="card side-card">
                <div class="card-header side-card-header">
                    <h2 class="h6 mb-0">Menú principal</h2>
                </div>
                <div class="card-body">
                    @foreach($menu_items as $item)
                        <a href="{{ $item['route'] }}" class="menu-link">
                            <div class="d-flex align-items-center">
                                <div class="menu-icon-wrapper">
                                    <i class="bi {{ $item['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="menu-text-title">{{ $item['title'] }}</div>
                                    <p class="menu-text-desc">{{ $item['text'] }}</p>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right menu-arrow"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


