@extends('layouts.app')

@section('content')
<style>
  .row-top { background:#e9f7ef !important; }
  .score-top { color:#146c43; font-weight:700; }
</style>

@php
  // Tomamos el id de la carrera para armar la URL del PDF
  $carreraId = request('carrera_id') ?? ($rows[0]->id_carrera ?? null);
@endphp

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Top 10 promedios — {{ $carrera }}</h5>
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
         href="{{ route('reportes.top_alumnos.pdf', ['carrera_id' => $carreraId]) }}">
        Descargar PDF
      </a>
    @else
      <button class="btn btn-outline-primary" disabled>Descargar PDF</button>
    @endif
  </div>
  <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary mt-2">← Volver a Reportes</a>
</div>
@endsection

