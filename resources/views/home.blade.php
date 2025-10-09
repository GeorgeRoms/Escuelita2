{{-- resources/views/home.blade.php --}}
@php
    // Ajusta los nombres de ruta si alguno es distinto en tu app
    $links = [
        ['label' => 'Carreras',               'route' => 'carreras.index',               'emoji' => '&#x1F393;'],
        ['label' => 'Alumnos',                'route' => 'alumnos.index',                'emoji' => '&#x1F465;'],
        ['label' => 'Materias',               'route' => 'materias.index',               'emoji' => '&#x1F4DA;'],
        ['label' => 'Cursos',                 'route' => 'cursos.index',                 'emoji' => '&#x1F4DD;'],
        ['label' => 'Profesores',             'route' => 'profesores.index',             'emoji' => '&#x1F9D1;&#x200D;&#x1F3EB;'],
        ['label' => 'Edificios',              'route' => 'edificios.index',              'emoji' => '&#x1F3E2;'],
        ['label' => 'Contactos Alumnos',      'route' => 'contactos-alumnos.index',      'emoji' => '&#x1F4C7;'],
        ['label' => 'Contactos Profesores',   'route' => 'contactos-profesores.index',   'emoji' => '&#x1F4C7;'],
        ['label' => 'Kardex',                 'route' => 'kardexes.index',               'emoji' => '&#x1F4CB;'],
        ['label' => 'Areas',                 'route' => 'areas.index',               'emoji' => '&#x1F4CB;'],
    ];
@endphp

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inicio</title>

    {{-- Si ya cargas Bootstrap/FontAwesome en tu layout, puedes quitar estas 2 líneas --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body { background:#0f1226; } .card{ border:0; border-radius:1rem }
        .card-title{ font-weight:700 } .app-title{ color:#fff } .emoji { font-size: 1.25rem; line-height: 1; }
    </style>

</head>
<body>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="app-title m-0">Panel principal</h1>
        @if (\Illuminate\Support\Facades\Route::has('logout'))
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                Log Out
            </button>
        </form>
        @endif
    </div>

    <div class="row g-3">
        @foreach ($links as $l)
            @if (\Illuminate\Support\Facades\Route::has($l['route']))
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <i class="fa {{ $l['emoji'] }} me-2"></i>{{ $l['label'] }}
                            </h5>
                            <p class="text-muted flex-grow-1">
                                Gestiona {{ strtolower($l['label']) }} (listar, crear, editar y eliminar).
                            </p>
                            <div class="mt-auto d-grid">
                                <a href="{{ route($l['route']) }}" class="btn btn-primary">
                                    Ir a {{ strtolower($l['label']) }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- Si una ruta no existe, no mostramos la tarjeta (así no truena) --}}
            @endif
        @endforeach
    </div>
</div>

{{-- Si quitaste el CSS de Bootstrap de arriba porque ya lo cargas, también quita este JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
