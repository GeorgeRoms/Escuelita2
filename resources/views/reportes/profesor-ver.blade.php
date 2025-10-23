@extends('layouts.app')

@section('content')
<div class="container">
  <h4 class="mb-1">Materias impartidas por profesor</h4>
  <div class="text-muted mb-3">
    <strong>Docente:</strong> {{ $docente }}
    @if($periodoEtiqueta) · <strong>Periodo:</strong> {{ $periodoEtiqueta }} @endif
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Código de profesor</th>
          <th>Docente</th>
          <th>Código de curso</th>
          <th>Materia</th>
          <th>Periodo</th>
          <th>Alumnos inscritos</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $r)
          <tr>
            <td>{{ $r->id_profesor }}</td>
            <td>{{ $r->docente }}</td>
            <td>{{ $r->id_curso }}</td>
            <td>{{ $r->materia }}</td>
            <td>{{ $r->periodo }}</td>
            <td>{{ $r->alumnos_inscritos }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-muted">Sin cursos para los filtros seleccionados.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
    <a href="{{ route('reportes.profesor.pdf', [
        'profesor_id' => request('profesor_id'),     // o el que uses
        'periodo_id'  => request('periodo_id')       // opcional
    ]) }}"
   class="btn btn-outline-danger mt-2"
   target="_blank">
  Descargar PDF
</a>
  </div>

  <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary mt-2">← Volver a Reportes</a>
</div>
@endsection
