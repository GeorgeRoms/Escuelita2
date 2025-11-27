@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Kardex académico</h3>

    {{-- =================================== --}}
    {{-- 1. Materias actualmente en curso    --}}
    {{-- =================================== --}}
    <h5 class="mb-2">Materias que actualmente se están cursando</h5>

    @if($cursando->isEmpty())
        <p class="text-muted">Actualmente no tienes materias en curso.</p>
    @else
        <div class="table-responsive mb-4">
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Materia</th>
                        <th>Docente</th>
                        <th>Intento</th>
                        <th>Periodo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursando as $item)
                        @php
                            $curso   = $item->curso;
                            $materia = $curso?->materia;
                            $prof    = $curso?->profesor;
                            $periodo = $curso?->periodo;
                        @endphp
                        <tr>
                            {{-- Clave de materia --}}
                            <td>{{ $materia?->id_materia ?? '-' }}</td>

                            {{-- Nombre de materia --}}
                            <td>{{ $materia?->nombre_mat ?? 'Sin nombre' }}</td>

                            {{-- Docente --}}
                            <td>
                                @if($prof)
                                    {{ trim(($prof->nombre ?? '') . ' ' . ($prof->apellido_pat ?? '') . ' ' . ($prof->apellido_mat ?? '')) ?: 'Sin nombre' }}
                                @else
                                    Por asignar
                                @endif
                            </td>

                            {{-- Intento --}}
                            <td>{{ $item->intento ?? '-' }}</td>

                            {{-- Periodo --}}
                            <td>
                                @if($periodo)
                                    {{ $periodo->anio ?? '' }}
                                    @if(($periodo->anio ?? null) && ($periodo->nombre ?? null)) - @endif
                                    {{ $periodo->nombre ?? $periodo->nombre_periodo ?? '' }}
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <hr class="my-4">

    {{-- =================================== --}}
    {{-- 2. Materias cursadas anteriormente  --}}
    {{-- =================================== --}}
    <h5 class="mb-2">Materias anteriormente cursadas</h5>

    @if($cursadas->isEmpty())
        <p class="text-muted">Todavía no tienes materias concluidas.</p>
    @else
        <div class="table-responsive mb-4">
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Materia</th>
                        <th>Docente</th>
                        <th>Intento</th>
                        <th>Periodo</th>
                        <th>Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursadas as $item)
                        @php
                            $curso   = $item->curso;
                            $materia = $curso?->materia;
                            $prof    = $curso?->profesor;
                            $periodo = $curso?->periodo;
                        @endphp
                        <tr>
                            {{-- Clave de materia --}}
                            <td>{{ $materia?->id_materia ?? '-' }}</td>

                            {{-- Nombre de materia --}}
                            <td>{{ $materia?->nombre_mat ?? 'Sin nombre' }}</td>

                            {{-- Docente --}}
                            <td>
                                @if($prof)
                                    {{ trim(($prof->nombre ?? '') . ' ' . ($prof->apellido_pat ?? '') . ' ' . ($prof->apellido_mat ?? '')) ?: 'Sin nombre' }}
                                @else
                                    Por asignar
                                @endif
                            </td>

                            {{-- Intento --}}
                            <td>{{ $item->intento ?? '-' }}</td>

                            {{-- Periodo --}}
                            <td>
                                @if($periodo)
                                    {{ $periodo->anio ?? '' }}
                                    @if(($periodo->anio ?? null) && ($periodo->nombre ?? null || $periodo->nombre_periodo ?? null)) - @endif
                                    {{ $periodo->nombre ?? $periodo->nombre_periodo ?? '' }}
                                @else
                                    N/A
                                @endif
                            </td>

                            {{-- Promedio --}}
                            <td>{{ $item->promedio !== null ? number_format($item->promedio, 2) : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <hr class="my-4">

    {{-- =================================== --}}
    {{-- 3. Materias por cursarse            --}}
    {{-- =================================== --}}
    <h5 class="mb-2">Materias que están por cursarse</h5>

    @if($porCursar->isEmpty())
        <p class="text-muted">No hay materias pendientes por cursar registradas en el plan.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Materia</th>
                        {{-- <th>Semestre</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($porCursar as $m)
                        <tr>
                            <td>{{ $m->id_materia }}</td>
                            <td>{{ $m->nombre_mat }}</td>
                            {{-- <td>{{ $m->semestre ?? '-' }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
@endsection
