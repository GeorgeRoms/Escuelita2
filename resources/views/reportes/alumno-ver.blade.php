@extends('layouts.app')

@section('content')
<div class="container">
  <h4 class="mb-1">Historial académico del alumno</h4>
  <div class="text-muted mb-3">
    <strong>No. Control:</strong> {{ $alumnoInfo->no_control ?? '' }} · 
    <strong>Nombre:</strong> {{ $alumnoInfo->nombre_completo ?? '' }}
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Materia</th>
          <th>Profesor</th>
          <th>Periodo</th>
          <th>Estado</th>
          <th>Intento</th>
          <th>Promedio</th>
          <th>Semestre</th>
          <th>Código del Curso</th>
        </tr>
      </thead>
      <tbody>
        @forelse($historial as $h)
          <tr>
            <td>{{ $h->materia }}</td>
            <td>{{ $h->profesor }}</td>
            <td>{{ $h->periodo }}</td>
            <td>{{ $h->estado }}</td>
            <td>{{ $h->intento }}</td>
            <td>{{ $h->promedio }}</td>
            <td>{{ $h->semestre }}</td>
            <td>{{ $h->id_curso }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-muted">Sin materias registradas.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary mt-2">← Volver a Reportes</a>
</div>
@endsection
