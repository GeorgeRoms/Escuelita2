@extends('layouts.app')

@section('content')
<style>
  .row-top { background:#e9f7ef !important; }
  .score-top { color:#146c43; font-weight:700; }
</style>

@php
  // IDs para construir la URL de PDF
  $carreraId = request('carrera_id') ?? ($rows[0]->id_carrera ?? null);
  // si el controller envía $periodo_id úsalo; si no, toma de la request
  $periodoId = (isset($periodo_id) ? $periodo_id : request('periodo_id')) ?: null;

  // Etiqueta mostrable (el controller debería enviar $periodo ya armada)
  $etiquetaPeriodo = $periodo ?? 'Todos los periodos';
@endphp

<div class="container mt-4">
    <h4 class="mb-1">Top 10 promedios — {{ $carrera }}</h4>
    <div class="text-muted mb-3">
    <strong>Periodo:</strong> {{ $etiquetaPeriodo }}
    </div>

  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-sm table-striped align-middle mb-0">
        <thead>
          <tr>
            <th style="width:120px;">No. Control</th>
            <th>Alumno</th>
            <th style="width:160px;">Promedio general</th>
            <th style="width:150px;">Cursos completados</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rows as $r)
            <tr>
              <td>{{ $r->no_control }}</td>
              <td>{{ $r->alumno }}</td>
              <td><strong>{{ number_format($r->promedio_general, 2) }}</strong></td>
              <td>{{ $r->cursos_contemplados }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted">Sin datos.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div>
    @if($carreraId)
      <a class="btn btn-outline-primary"
         href="{{ route('reportes.top_alumnos.pdf', ['carrera_id' => $carreraId, 'periodo_id' => $periodoId]) }}">
        Descargar PDF
      </a>
    @else
      <button class="btn btn-outline-primary" disabled>Descargar PDF</button>
    @endif
  </div>
  <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary mt-2">← Volver a Reportes</a>
</div>
@endsection

