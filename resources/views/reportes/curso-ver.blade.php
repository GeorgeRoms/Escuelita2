@extends('layouts.app')

@section('content')
<div class="container">
  <h4 class="mb-1">Alumnos por Curso</h4>
  @if($cursoInfo)
    <div class="text-muted mb-3">
      <strong>Curso #:</strong> {{ $cursoInfo->id_curso }} ·
      <strong>Materia:</strong> {{ $cursoInfo->materia }} ·
      <strong>Periodo:</strong> {{ $cursoInfo->periodo }} ·
      <strong>Profesor:</strong> {{ $cursoInfo->profesor }}
    </div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>No. Control</th>
          <th>Alumno</th>
          <th>Semestre</th>
          <th>Intento</th>
          <th>Calificación</th>
          <th>Resultado</th>
        </tr>
      </thead>
      <tbody>
        @forelse($alumnos as $row)
          <tr>
            <td>{{ $row->no_control }}</td>
            <td>{{ $row->alumno }}</td>
            <td>{{ $row->semestre }}</td>
            <td>{{ $row->intento }}</td>
            <td>{{ number_format($row->calificacion, 2) }}</td>
            <td>{{ $row->resultado }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-muted">Sin inscripciones “Inscrito” para este curso.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <a href="{{ route('reportes.curso.pdf', ['curso_id' => $cursoInfo->id_curso ?? 0]) }}"
      class="btn btn-outline-danger mt-3" target="_blank">
      Descargar PDF
    </a>
  </div>

  <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary mt-2">← Elegir otro curso</a>
</div>
@endsection
