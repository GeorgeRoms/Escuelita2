@extends('layouts.app')
@section('title','Escuelita | Inicio')

@php
    // Ajusta los nombres de ruta si alguno es distinto en tu app
    $links = [
        ['label' => 'Mi perfil',               'route' => 'carreras.index',               'emoji' => '&#x1F393;'],
        ['label' => 'Kardex',               'route' => 'carreras.index',               'emoji' => '&#x1F4DA;'],
        ['label' => 'Horarios',               'route' => 'carreras.index',               'emoji' => '&#x1F4C5;'],
        ['label' => 'Datos personales',             'route' => 'profesores.index',             'emoji' => '&#x1F9D1;&#x200D;&#x1F3EB;'],
        ['label' => 'Calificaciones',             'route' => 'profesores.index',             'emoji' => '&#x1F9D1;&#x200D;&#x1F3EB;'],
        ['label' => 'Carga de materias',             'route' => 'profesores.index',             'emoji' => '&#x1F9D1;&#x200D;&#x1F3EB;'],
        ['label' => 'Curso actual',             'route' => 'profesores.index',             'emoji' => '&#x1F4C5;'],
          
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
                <h1 class="h3 mb-1">LORANCA GOMEZ MARIN</h1>
                <p class="mb-0 opacity-75">NO. de control: 202521545</p>
            </div>
            <div>
                <p class="mb-0 opacity-75">Carrera</p>
                <h1 class="h3 mb-1">Electrónica</h1>
            </div>
            
            <div>
                <p class="mb-0 opacity-75">Semestre</p>
                <h1 class="h3 mb-1">5</h1>
            </div>
            <div>
                <p class="mb-0 opacity-75">Status</p>
                <h1 class="h3 mb-1">Vigente</h1>
            </div>
            <div>
                <p class="mb-0 opacity-75">Promedio</p>
                <h1 class="h3 mb-1">9.98</h1>
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


