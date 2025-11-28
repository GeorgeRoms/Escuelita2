@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Historial de cursos</h3>

    @if($cursos->isEmpty())
        <p class="text-muted">No se encontraron cursos anteriores.</p>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Curso</th>
                        <th>Periodo</th>
                        <th>Horario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursos as $c)
                        <tr>
                            <td>{{ $c->materia->nombre_mat ?? 'Materia sin nombre' }}</td>
                            <td>#{{ $c->id_curso }}
                            @if(!empty($curso->grupo))
                            · Grupo {{ $curso->grupo }}
                            @endif</td>
                            <td>
                                @if($c->periodo)
                                    {{ $c->periodo->nombre ?? $c->periodo->nombre_periodo ?? '' }} {{ $c->periodo->anio ?? '' }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ $c->dia_semana ?? '' }}
                                · {{ substr((string)$c->hora_inicio,10,6) }} a {{ substr((string)$c->hora_fin,10,6) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br><a href="{{ route('home.profesor') }}" class="btn btn-outline-secondary mb-3">&larr; Volver</a>
    @endif
</div>
@endsection
