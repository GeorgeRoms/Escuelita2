@extends('layouts.app')

@section('title','Escuelita | Capturar calificaciones')

@section('content')
<div class="container">
    <h3 class="mb-3">Capturar calificaciones</h3>

    <p class="mb-1">
        <strong>Materia:</strong> {{ $curso->materia->nombre_mat ?? 'Materia sin nombre' }}<br>
        <strong>Curso:</strong>
        #{{ $curso->id_curso }}
        @if(!empty($curso->grupo))· Grupo {{ $curso->grupo }}@endif
        <br><strong>Periodo:</strong>
        @if($curso->periodo)
             {{ $curso->periodo->nombre ?? $curso->periodo->nombre_periodo ?? '' }} {{ $curso->periodo->anio ?? '' }}
        @else
            N/A
        @endif
    </p>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profesor.curso.calificaciones.guardar', $curso->id_curso) }}" class="mt-3">
        @csrf

        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle">
                <thead>
                    <tr>
                        <th>No. Control</th>
                        <th>Alumno</th>
                        <th>Intento</th>
                        <th>Calificación</th>
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
                            <td style="width: 140px;">
                                <input type="number"
                                       name="calificaciones[{{ $insc->id }}]"
                                       class="form-control form-control-sm @error('calificaciones.'.$insc->id) is-invalid @enderror"
                                       min="0" max="100" step="0.01"
                                       value="{{ old('calificaciones.'.$insc->id, $insc->promedio) }}">
                                @error('calificaciones.'.$insc->id)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button class="btn btn-primary mt-2">
            Guardar calificaciones
        </button><br><br>
        <a href="{{ route('profesor.cursos.vigentes') }}" class="btn btn-outline-secondary mb-3">&larr; Volver</a>
    </form>
</div>
@endsection
