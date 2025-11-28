@extends('layouts.app')

@section('title','Escuelita | Mis cursos vigentes')

@section('content')
<div class="container">
    <h3 class="mb-4">Mis cursos vigentes</h3>

    @if($cursos->isEmpty())
        <p class="text-muted">Actualmente no tienes cursos asignados.</p>
    @else
        <div class="list-group">
            @foreach($cursos as $c)
                @php
                    // Horario
                    $dias = $c->dia_semana ?: '—';

                    $horaIni = $c->hora_inicio
                        ? \Carbon\Carbon::parse($c->hora_inicio)->format('H:i')
                        : '—';

                    $horaFin = $c->hora_fin
                        ? \Carbon\Carbon::parse($c->hora_fin)->format('H:i')
                        : '—';

                    // Periodo
                    if ($c->periodo) {
                        $periodoTexto = ($c->periodo->nombre ?? $c->periodo->nombre_periodo ?? '') . ' ' .
                                        ($c->periodo->anio ?? '');
                    } else {
                        $periodoTexto = 'No asignado';
                    }

                    // Aula / Edificio
                    $aula      = $c->aula ?? null;
                    $edificio  = $aula?->edificio ?? null;

                    $edificioNombre = $edificio->nombre ?? null;   // p.ej. "Edificio B"
                    $edificioCodigo = $edificio->codigo ?? null;   // p.ej. "B"
                    $salon          = $aula->salon   ?? null;       // p.ej. "B202"

                    if ($edificioNombre || $salon) {
                        // Ejemplo: "Edificio B · B202" ó "Edificio B" si no hay salón
                        $edificioTexto = trim(
                            ($edificioNombre ?: ('Edificio '.$edificioCodigo)) .
                            ($salon ? ' · '.$salon : '')
                        );
                    } else {
                        $edificioTexto = 'Sin aula asignada';
                    }
                @endphp

                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        {{-- Nombre de la materia --}}
                        <div class="fw-semibold">
                            {{ $c->materia->nombre_mat ?? 'Materia sin nombre' }}
                        </div>

                        {{-- Línea de curso + horario --}}
                        <div class="small text-muted">
                            Curso #{{ $c->id_curso }}
                            @if($dias !== '—')
                                · {{ $dias }}
                            @endif
                            @if($horaIni !== '—' && $horaFin !== '—')
                                · {{ $horaIni }} – {{ $horaFin }}
                            @endif
                        </div>

                        {{-- Periodo --}}
                        <div class="small text-muted">
                            Periodo: {{ $periodoTexto }}
                        </div>

                        {{-- Edificio / Aula --}}
                        <div class="small text-muted">
                            Aula / Edificio: {{ $edificioTexto }}
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('profesor.curso.calificaciones', $c->id_curso) }}"
                           class="btn btn-sm btn-outline-primary mb-1">
                            Calificar
                        </a>
                        <br>
                        <a href="{{ route('profesor.curso.lista', $c->id_curso) }}"
                           class="small text-decoration-none">
                            Ver lista
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <br><a href="{{ route('home.profesor') }}" class="btn btn-outline-secondary mb-3">&larr; Volver</a>
    @endif
</div>
@endsection


