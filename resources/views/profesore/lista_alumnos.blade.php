@extends('layouts.app')

@section('title','Escuelita | Lista de alumnos')

@section('content')
<div class="container">
    <h3 class="mb-3">Lista de alumnos</h3>

    <p class="mb-1">
        <strong>Materia:</strong> {{ $curso->materia->nombre_mat ?? 'Materia sin nombre' }}<br>
        <strong>Curso:</strong>
        #{{ $curso->id_curso }}
        @if(!empty($curso->grupo))
            · Grupo {{ $curso->grupo }}
        @endif <br>
        <strong>Horario:</strong>
        {{ $curso->dia_semana ?? '' }}
        de {{ substr((string)$curso->hora_inicio,10,6) }} a {{ substr((string)$curso->hora_fin,10,6) }}
    </p>

    <div class="table-responsive mt-3">
        <table class="table table-striped table-sm align-middle">
            <thead>
                <tr>
                    <th>No. Control</th>
                    <th>Alumno</th>
                    <th>Intento</th>
                    <th>Promedio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inscripciones as $insc)
                    @php $a = $insc->alumno; @endphp
                    <tr>
                        <td>{{ $insc->alumno_no_control }}</td>
                        <td>
                            @if($a)
                                {{ trim(($a->nombre ?? '') . ' ' . ($a->apellido_pat ?? '') . ' ' . ($a->apellido_mat ?? '')) }}
                            @else
                                Alumno sin ficha
                            @endif
                        </td>
                        <td>{{ $insc->intento ?? '-' }}</td>
                        <td>{{ $insc->promedio !== null ? number_format($insc->promedio,2) : 'N/A' }}</td>
                        <td>{{ $insc->estado ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Ocultar botones y cosas de UI al imprimir --}}
<style>
    @media print {
        .no-print,
        .navbar,
        .navbar * {
            display: none !important;
        }

        body {
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 100%;
        }
    }
</style>
    <button class="btn btn-primary" onclick="window.print()">
        <i class="bi bi-printer me-1"></i> Imprimir lista
    </button><br><br>
    <a href="{{ route('profesor.cursos.vigentes') }}" class="btn btn-outline-secondary mb-3">&larr; Volver</a>
</div>
@endsection
