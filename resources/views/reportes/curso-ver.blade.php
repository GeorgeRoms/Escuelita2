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
          <th>No. control</th>
          <th>Alumno</th>
          <th>Semestre</th>
          <th>Intento</th>
          <th>Profesor</th>
          <th>Materia</th>
          <th>Periodo</th>
        </tr>
      </thead>
      <tbody>
        @forelse($alumnos as $row)
          <tr>
            <td>{{ $row->no_control }}</td>
            <td>{{ $row->alumno }}</td>
            <td>{{ $row->semestre }}</td>
            <td>{{ $row->intento }}</td>
            <td>{{ $row->profesor }}</td>
            <td>{{ $row->materia }}</td>
            <td>{{ $row->periodo }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-muted">Sin inscripciones “Inscrito” para este curso.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary mt-2">← Elegir otro curso</a>
</div>
@endsection
