@section('content')

@if (session('error'))
    <div class="alert alert-danger mt-2">
        {{ session('error') }}
    </div>
@endif

@extends('layouts.app')
@section('title','Escuelita | Inicio')

@section('nav_links')
    {{-- Si el alumno solo debe ver "Inicio" y "Reportes", puedes dejar solo esos enlaces aquí: --}}
    <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">Inicio</a></li>
    {{-- Puedes añadir otros enlaces si son específicos del alumno, o déjalo completamente vacío si no debe ver ninguno. --}}
@endsection

@php
    // Si el controlador te manda un $alumno (modelo Alumno), úsalo.
    // Si no, toma al usuario autenticado como respaldo.
    $alumno = $alumno ?? Auth::user();

    // DEFINICIÓN DE ENLACES DE NAVEGACIÓN (menú lateral)
    $links = [
        [
            'label' => 'Mi perfil',
            'route' => 'alumno.perfil',
            'emoji' => '&#x1F393;', // birrete
        ],
        [
            'label' => 'Kardex',
            'route' => 'alumno.kardex',
            'emoji' => '&#x1F4DA;', // libros
        ],
        [
            'label' => 'Horarios',
            'route' => 'alumno.horarios',
            'emoji' => '&#x1F4C5;', // calendario
        ],
        [
            'label' => 'Datos personales',
            'route' => 'alumno.datos_personales',
            'emoji' => '&#x1F4F2;', // teléfono
        ],
        // [
        //     'label' => 'Calificaciones',
        //     'route' => 'alumno.calificaciones',
        //     'emoji' => '&#x1F4DD;', // bloc de notas
        // ],
        [
            'label' => 'Carga de materias',
            'route' => 'alumno.carga_materias',
            'emoji' => '&#x1F4C4;', // hoja
        ],
    ];

    // ===== Nombre de la carrera =====
    $carreraNombre = 'N/A';

    if (isset($alumno->carreras)) {
        if ($alumno->carreras instanceof \Illuminate\Support\Collection) {
            $carreraNombre = $alumno->carreras->pluck('nombre_carr')->join(', ');
        } else {
            $carreraNombre = (string) $alumno->carreras;
        }
    } elseif (isset($alumno->carrera)) {
        if (is_string($alumno->carrera)) {
            $carreraNombre = $alumno->carrera;
        } else {
            $carreraNombre = $alumno->carrera->nombre_carr ?? 'N/A';
        }
    }

    // Formatear promedio que viene del controlador
    $promedioMostrar = $promedio !== null
        ? number_format($promedio, 2)
        : 'N/A';

    // Datos que se mostrarán en la parte superior derecha
    $info_superior = [
        [
            'label' => 'Carrera',
            'value' => $carreraNombre,
        ],
        [
            'label' => 'Semestre',
            'value' => $alumno->semestre ?? 'N/A',
        ],
        [
            'label' => 'Estatus',
            'value' => $estatus ?? 'N/A',
        ],
        [
            'label' => 'Promedio',
            'value' => $promedioMostrar,
        ],
    ];


    // Botones destacados (debajo del saludo)
    $bts = [
        [
            'label' => 'Mis cursos activos',
            'route' => 'alumno.cursos_activos',
            'emoji' => '&#x1F4D6;',
        ],
        // [
        //     'label' => 'Plan de materias',
        //     'route' => 'alumno.plan_materias',
        //     'emoji' => '&#x1F4DA;',
        // ],
    ];
@endphp

@section('content')

<style>
    /* Estilos base del hero */
    .home-hero{
        background: linear-gradient(16deg, #184477 0%, #184477 60%);
        color:#fff;
        border-radius: 1.25rem;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.62);
        padding: 1.5rem;
    }

    /* ESTILOS ESPECÍFICOS PARA EL NUEVO DISEÑO DE BOCETO */

    /* Contenedor principal que envuelve al hero y la navegación */
    .dashboard-container {
        margin-bottom: 2rem;
    }

    /* Recuadro principal vertical (columna izquierda) */
    .vertical-hero-box {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        padding: 1.5rem;
        min-height: auto;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        text-align: center;
    }

    /* Avatar circular grande */
    .avatar-circle {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(0,0,0,.15);
    }
    .avatar-circle span {
        font-size: 2.25rem;
        font-weight: 600;
    }

    /* Encabezado de bienvenida */
    .hero-title {
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: .25rem;
    }
    .hero-subtitle {
        font-size: .9rem;
        opacity: .85;
        margin-bottom: 1rem;
    }

    /* Botón principal "Ver mis cursos" dentro del recuadro */
    .btn-main-hero {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: .9rem;
        padding: .5rem .85rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.25);
        color: #184477;
        background: #f1c40f;
        font-weight: 500;
        text-decoration: none;
        transition: background .15s ease, transform .15s ease, box-shadow .15s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,.15);
    }

    .btn-main-hero:hover {
        background: #f39c12;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(0,0,0,.22);
        color:#184477;
    }

    /* Sección de "chips" informativos debajo del botón principal (Carrera, Semestre, etc.) */
    .info-chips {
        margin-top: 1rem;
        margin-bottom: 1.25rem;
        width: 100%;
    }
    .info-chip {
        background: rgba(255,255,255,0.08);
        border-radius: 999px;
        padding: .45rem .85rem;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        margin: 0 .25rem .4rem 0;
        font-size: .8rem;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .info-chip-label {
        font-weight: 500;
        opacity: .9;
    }
    .info-chip-value {
        font-weight: 600;
    }

    /* Bloque de "accesos rápidos" dentro del recuadro */
    .quick-links {
        margin-top: auto;
        width: 100%;
    }
    .quick-links-title {
        font-size: .9rem;
        margin-bottom: .35rem;
        text-align: left;
        opacity: .9;
    }
    .quick-links small {
        font-size: .8rem;
        opacity: .75;
        display: block;
        margin-bottom: .65rem;
        text-align: left;
    }

    .quick-links-list {
        display: flex;
        flex-direction: column;
        gap: .4rem;
        width:100%;
    }
    .quick-link-item {
        display: flex;
        align-items: center;
        gap: .5rem;
        text-decoration: none;
        color:#fff;
        font-size:.87rem;
        padding:.45rem .6rem;
        border-radius:.6rem;
        transition: background .15s ease, transform .12s ease;
    }
    .quick-link-item:hover {
        background: rgba(255,255,255,0.08);
        transform: translateY(-1px);
        text-decoration:none;
        color:#fff;
    }
    .quick-link-item .quick-icon {
        width:24px;
        height:24px;
        border-radius:999px;
        border:1px solid rgba(255,255,255,0.4);
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1rem;
        background: rgba(24,68,119,0.25);
    }

    /* Tarjetas de la derecha (Accesos rápidos / Recordatorios) */
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

    /* Botones de acción principal (Mis cursos activos, Plan de materias) */
    .hero-actions {
        margin-top: 1rem;
        margin-bottom: 1rem;
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    .hero-action-btn {
        border-radius: 999px;
        font-size: .85rem;
        padding: .45rem .9rem;
        font-weight: 500;
        border: 1px solid rgba(255,255,255,0.3);
        color:#184477;
        background-color: #f1c40f;
        display:inline-flex;
        align-items:center;
        gap:.35rem;
        text-decoration:none;
        transition: background .15s ease, transform .15s ease, box-shadow .15s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,.18);
    }

    .hero-action-btn:hover {
        background:#f39c12;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(0,0,0,.25);
        color:#184477;
    }

    /* Botones secundarios dentro de las tarjetas */
    .btn-link-ghost {
        font-size: .8rem;
        padding: .25rem .6rem;
        border-radius: 999px;
        border:1px solid rgba(24,68,119,0.18);
        background:#f8fafc;
        color:#184477;
    }
    .btn-link-ghost:hover {
        background:#edf2ff;
        color:#0b1f39;
    }

    /* Tarjetas del menú principal (derecha) */
    .nav-card {
        transition: transform .15s ease, box-shadow .15s ease;
        border:0;
        border-radius: 0.75rem;
        background-color: #ffffff;
    }
    .nav-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,.1);
    }
    .nav-card .card-title, .nav-card .text-muted {
        color:#111827;
    }

    /* Badge/emoji circular para cada acción del menú */
    .icon-pill {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        display:flex;
        align-items:center;
        justify-content:center;
        background: rgba(241,196,15,.18);
        border:1px solid rgba(241,196,15,.35);
        color: #184477;
        flex: 0 0 44px;
    }
    .emoji{ font-size: 1.25rem; line-height: 1; }

    /* Adaptación para móviles */
    @media (max-width: 991.98px) {
        .vertical-hero-box {
            margin-bottom: 1rem;
        }
    }
</style>

{{-- Contenedor principal --}}
<div class="home-hero dashboard-container">
    <div class="row g-4">

        {{-- COLUMNA IZQUIERDA: Recuadro Vertical --}}
        <div class="col-12 col-lg-4">
            <div class="vertical-hero-box">
                {{-- Avatar con iniciales del alumno --}}
                @php
                    $nombre = trim(($alumno->nombre ?? $alumno->name ?? 'Alumno') . ' ' . ($alumno->apellido_pat ?? ''));
                    $iniciales = collect(explode(' ', $nombre))
                        ->filter()
                        ->map(fn($p) => mb_substr($p,0,1))
                        ->join('');
                    $iniciales = $iniciales ?: 'AL';
                @endphp

                <div class="avatar-circle">
                    <span>{{ $iniciales }}</span>
                </div>

                {{-- Título de bienvenida --}}
                <div class="hero-title">
                    ¡Hola, {{ $alumno->nombre ?? $alumno->name ?? 'Alumno' }}!
                </div>
                <div class="hero-subtitle">
                    Bienvenido a tu panel académico. Aquí podrás consultar tu información escolar, horarios y más.
                </div>

                {{-- Botones principales (cursos activos, plan de materias, etc.) --}}
                <div class="hero-actions">
                    @foreach ($bts as $btn)
                        @if (Route::has($btn['route']))
                            <a href="{{ route($btn['route']) }}" class="hero-action-btn">
                                <span>{!! $btn['emoji'] !!}</span>
                                <span>{{ $btn['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>

                {{-- Info chips (Carrera, Semestre, Status, Promedio) --}}
                {{-- <div class="info-chips">
                    @foreach ($info_superior as $info)
                        @if(!empty($info['value']))
                            <div class="info-chip">
                                <span class="info-chip-label">{{ $info['label'] }}:</span>
                                <span class="info-chip-value">{{ $info['value'] }}</span>
                            </div>
                        @endif
                    @endforeach
                </div> --}}

                {{-- Quick links (enlaces rápidos dentro del recuadro) --}}
                {{-- <div class="quick-links">
                    <div class="quick-links-title">Accesos rápidos</div>
                    <small>Consulta tus trámites y documentos más usados:</small>

                    <div class="quick-links-list">
                        @if (Route::has('alumno.kardex'))
                            <a href="{{ route('alumno.kardex') }}" class="quick-link-item">
                                <div class="quick-icon">📚</div>
                                <span>Kardex y avance académico</span>
                            </a>
                        @endif
                        @if (Route::has('alumno.horarios'))
                            <a href="{{ route('alumno.horarios') }}" class="quick-link-item">
                                <div class="quick-icon">🕒</div>
                                <span>Horarios de clase</span>
                            </a>
                        @endif
                        @if (Route::has('alumno.calificaciones'))
                            <a href="{{ route('alumno.calificaciones') }}" class="quick-link-item">
                                <div class="quick-icon">✅</div>
                                <span>Consultar calificaciones</span>
                            </a>
                        @endif
                    </div>
                </div> --}}
            </div>
        </div>

        {{-- COLUMNA DERECHA: tarjetas y menú principal --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-3">
            {{-- Tarjeta de recordatorios / info --}}
            <div class="card side-card">
                <div class="card-header side-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h6 mb-1">Resumen de tu información:</h2>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach ($info_superior as $info)
                            <div class="col-12 col-md-6 col-lg-3">
                                <p class="mb-0 small opacity-75 text-muted">{{ $info['label'] }}:</p>
                                <p class="h6 mb-1 text-dark">{{ $info['value'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Contenedor del Menú principal (en lista vertical) --}}
            <div class="card side-card flex-grow-1">
                <div class="card-header side-card-header">
                    <h2 class="h6 mb-0">Menú principal</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($links as $l)
                            @if (Route::has($l['route']))
                                <div class="col-12">
                                    <a href="{{ route($l['route']) }}" class="text-decoration-none d-block">
                                        <div class="card nav-card h-100">
                                            <div class="card-body d-flex align-items-center gap-3 py-2">
                                                <div class="icon-pill">
                                                    <span class="emoji">{!! $l['emoji'] !!}</span>
                                                </div>
                                                <div class="flex-grow-1 text-left">
                                                    <h5 class="card-title mb-0 text-dark">{{ $l['label'] }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- ✔️ Bloque opcional: mostrar materias si el controlador envía $materias --}}
                    {{-- @if (isset($materias))
                        <div class="mt-4">
                            <h4 class="text-dark mb-3">Materias de tu programa académico</h4>
                            <div class="list-group">
                                @foreach ($materias as $m)
                                    <div class="list-group-item">
                                        📘 {{ $m->nombre_mat }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif --}}
                    {{-- ✔️ FIN DEL BLOQUE AGREGADO --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

