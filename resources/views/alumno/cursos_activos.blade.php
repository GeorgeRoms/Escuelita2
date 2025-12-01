@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Cursos activos</h3>

    @if($cursos->isEmpty())
        <p class="text-muted">No tienes cursos activos actualmente.</p>
    @else
        <div class="list-group">
            @foreach($cursos as $c)
                <div class="list-group-item">

                    {{-- Encabezado: nombre de la materia + badge de estado --}}
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong>{{ $c->materia->nombre_mat ?? 'Materia sin nombre' }}</strong>
                        <span class="badge bg-success">Activo</span>
                    </div>

                    {{-- Profesor --}}
                    <div class="small text-muted mb-1">
                        Profesor:
                        <strong>
                            @if($c->profesor)
                                {{-- Ajusta estos campos según tu tabla de profesores --}}
                                {{ trim(($c->profesor->nombre ?? '') . ' ' . ($c->profesor->apellido_pat ?? '') . ' ' . ($c->profesor->apellido_mat ?? '')) ?: 'Sin nombre' }}
                            @else
                                Por asignar
                            @endif
                        </strong>
                    </div>

                    {{-- Horario + Aula --}}
                    <div class="small">
                        {{ $c->dia_semana ?? 'Día no asignado' }}
                        —
                        {{ substr((string) $c->hora_inicio, 10, 6) }}
                        a
                        {{ substr((string) $c->hora_fin, 10, 6) }}

                        @if($c->aula)
                            · Aula {{ $c->aula->nombre ?? $c->aula->id }}
                            @if($c->aula->edificio ?? false)
                                ({{ $c->aula->edificio->nombre_edificio ?? $c->aula->edificio->nombre ?? '' }})
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <br><a href="{{ route('alumno.home') }}" class="btn btn-outline-secondary mb-3">&larr; Volver</a>
    @endif
</div>
@endsection
