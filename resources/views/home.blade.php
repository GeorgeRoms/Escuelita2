@extends('layouts.app')
@section('title','Escuelita | Inicio')

@php
    // Ajusta los nombres de ruta si alguno es distinto en tu app
    $links = [
        ['label' => 'Carreras',               'route' => 'carreras.index',               'emoji' => '&#x1F393;'],
        ['label' => 'Alumnos',                'route' => 'alumnos.index',                'emoji' => '&#x1F465;'],
        ['label' => 'Materias',               'route' => 'materias.index',               'emoji' => '&#x1F4DA;'],

        // Oferta académica
        ['label' => 'Cursos',                 'route' => 'cursos.index',                 'emoji' => '&#x1F4DD;'],
        ['label' => 'Aulas',                  'route' => 'aulas.index',                  'emoji' => '&#x1F3EB;'],
        ['label' => 'Edificios',              'route' => 'edificios.index',              'emoji' => '&#x1F3E2;'],
        ['label' => 'Periodos',               'route' => 'periodos.index',               'emoji' => '&#x1F4C5;'],

        // Operación principal
        ['label' => 'Inscripciones',          'route' => 'inscripciones.index',          'emoji' => '&#x270D;&#xFE0F;'],

        // (Opcional) Módulos nuevos recomendados
        ['label' => 'Programa de alumnos',    'route' => 'alumno-carreras.index',         'emoji' => '&#x1F517;'],


        // Otros existentes
        ['label' => 'Profesores',             'route' => 'profesores.index',             'emoji' => '&#x1F9D1;&#x200D;&#x1F3EB;'],
        ['label' => 'Contacto de alumnos',      'route' => 'contactos-alumnos.index',      'emoji' => '&#x1F4C7;'],
        ['label' => 'Contacto de profesores',   'route' => 'contactos-profesores.index',   'emoji' => '&#x1F4C7;'],
        ['label' => 'Áreas',                  'route' => 'areas.index',                  'emoji' => '&#x1F4C1;'],
        ['label' => 'Reportes',                  'route' => 'reportes.index',                  'emoji' => '&#x1F4C1;'],
        
    ];
@endphp

@section('content')

<style>
    .home-hero{
        background: linear-gradient(135deg, var(--brand) 0%, var(--brand-3) 60%);
        color:#fff;
        border-radius: 1.25rem;
        box-shadow: 0 10px 24px rgba(0,0,0,.12);
    }
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
</style>

<div class="home-hero p-4 p-md-5 mb-4">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('images/escuelita-logo2.png') }}" alt="Escuelita" width="200" height="200" class="rounded-1">
            <div>
                <h1 class="h3 mb-1">Panel principal</h1>
                <p class="mb-0 opacity-75">Administra carreras, oferta (cursos, aulas, periodos) e inscripciones.</p>
            </div>
        </div>

        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                
            </form>
        @endauth
    </div>
</div>

<div class="row g-3">
    @foreach ($links as $l)
        @if (Route::has($l['route']))
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route($l['route']) }}" class="text-decoration-none">
                    <div class="card hover-card h-100">
                        <div class="card-body d-flex align-items-start gap-3">
                            <div class="icon-pill">
                                <span class="emoji">{!! $l['emoji'] !!}</span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1 text-dark">{{ $l['label'] }}</h5>
                                <p class="text-muted mb-0">Gestiona {{ strtolower($l['label']) }}.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endif
    @endforeach
</div>
@endsection


