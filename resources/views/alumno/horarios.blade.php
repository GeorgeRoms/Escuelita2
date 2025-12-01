@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Horarios</h3>

    {{-- ========================= --}}
    {{-- 1. Materias en curso      --}}
    {{-- ========================= --}}
    <h5 class="mb-2">Materias en curso</h5>

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
                        <th>Horario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursando as $h)
                        @php
                            $curso   = $h->curso;
                            $materia = $curso?->materia;
                            $prof    = $curso?->profesor;
                            $periodo = $curso?->periodo;
                        @endphp
                        <tr>
                            {{-- Clave de materia (usamos id_materia) --}}
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

                            {{-- Intento (Normal / Repite / Especial) --}}
                            <td>{{ $h->intento ?? '-' }}</td>

                            {{-- Periodo --}}
                            <td>
                                @if($periodo)
                                    {{ $periodo->anio }} - {{ $periodo->nombre }}
                                @else
                                    N/A
                                @endif
                            </td>

                            {{-- Horario: días + horas --}}
                            <td>
                                {{ $curso?->dia_semana ?? 'Día no asignado' }}
                                · {{ $curso?->hora_inicio ? substr((string) $curso->hora_inicio, 10, 6) : '??:??' }}
                                a {{ $curso?->hora_fin ? substr((string) $curso->hora_fin, 10, 6) : '??:??' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <hr class="my-4">

    {{-- ========================= --}}
    {{-- 2. Materias cursadas      --}}
    {{-- ========================= --}}
    <h5 class="mb-2">Materias anteriormente cursadas</h5>

    @if($cursadas->isEmpty())
        <p class="text-muted">Todavía no tienes materias cursadas anteriormente.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Materia</th>
                        <th>Docente</th>
                        <th>Intento</th>
                        <th>Periodo</th>
                        <th>Horario</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursadas as $h)
                        @php
                            $curso   = $h->curso;
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
                            <td>{{ $h->intento ?? '-' }}</td>

                            {{-- Periodo --}}
                            <td>
                                @if($periodo)
                                    {{ $periodo->anio }} - {{ $periodo->nombre }}
                                @else
                                    N/A
                                @endif
                            </td>

                            {{-- Horario --}}
                            <td>
                                {{ $curso?->dia_semana ?? 'Día no asignado' }}
                                · {{ $curso?->hora_inicio ? substr((string) $curso->hora_inicio, 0, 5) : '??:??' }}
                                a {{ $curso?->hora_fin ? substr((string) $curso->hora_fin, 0, 5) : '??:??' }}
                            </td>

                            {{-- Calificación (promedio en inscripciones) --}}
                            <td>{{ $h->promedio !== null ? number_format($h->promedio, 2) : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br><a href="{{ route('alumno.home') }}" class="btn btn-outline-secondary mb-3">&larr; Volver</a>
    @endif

</div>
@endsection
