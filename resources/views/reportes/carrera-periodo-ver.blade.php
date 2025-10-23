{{-- resources/views/reportes/carrera-periodo-ver.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
  <h4 class="mb-1">Resumen — {{ $carrera }}</h4>
  <div class="text-muted mb-3"><strong>Periodo:</strong> {{ $periodoEtiqueta }}</div>

  {{-- KPIs (RS1) --}}
  @php $k = $kpis[0] ?? null; @endphp
  <div class="row g-3 mb-3">
    <div class="col-md-3"><div class="card"><div class="card-body">
      <div class="text-muted">Alumnos únicos</div>
      <div class="fs-3">{{ $k['alumnos_unicos'] ?? 0 }}</div>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <div class="text-muted">Inscripciones</div>
      <div class="fs-3">{{ $k['total_inscripciones'] ?? 0 }}</div>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <div class="text-muted">Materias</div>
      <div class="fs-3">{{ $k['materias_distintas'] ?? 0 }}</div>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body">
      <div class="text-muted">Profesores</div>
      <div class="fs-3">{{ $k['profesores_distintos'] ?? 0 }}</div>
    </div></div></div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card"><div class="card-body">
      <div class="text-muted">Intentos Normal</div>
      <div class="fs-4">{{ $k['intentos_normal'] ?? 0 }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
      <div class="text-muted">Intentos Repite</div>
      <div class="fs-4">{{ $k['intentos_repite'] ?? 0 }}</div>
    </div></div></div>
    <div class="col-md-4"><div class="card"><div class="card-body">
      <div class="text-muted">Intentos Especial</div>
      <div class="fs-4">{{ $k['intentos_especial'] ?? 0 }}</div>
    </div></div></div>
  </div>

  {{-- RS2: Por materia --}}
  <h5>Alumnos por materia</h5>
  <div class="table-responsive mb-4">
    <table class="table table-striped align-middle">
      <thead><tr><th>ID Materia</th><th>Materia</th><th>Alumnos inscritos</th></tr></thead>
      <tbody>
        @forelse($porMateria as $r)
          <tr>
            <td>{{ $r['id_materia'] }}</td>
            <td>{{ $r['materia'] }}</td>
            <td>{{ $r['alumnos_inscritos'] }}</td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-muted">Sin datos.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- RS3: Por profesor --}}
  <h5>Alumnos por profesor</h5>
  <div class="table-responsive mb-4">
    <table class="table table-striped align-middle">
      <thead><tr><th>ID Profesor</th><th>Profesor</th><th>Alumnos inscritos</th></tr></thead>
      <tbody>
        @forelse($porProfesor as $r)
          <tr>
            <td>{{ $r['id_profesor'] }}</td>
            <td>{{ $r['profesor'] }}</td>
            <td>{{ $r['alumnos_inscritos'] }}</td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-muted">Sin datos.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- RS4: Detalle por alumno --}}
  <h5>Detalle por alumno</h5>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>No. control</th>
          <th>Alumno</th>
          <th>Semestre</th>
          <th># Inscripciones</th>
          <th>Especial</th>
          <th>Repite</th>
        </tr>
      </thead>
      <tbody>
        @forelse($porAlumno as $r)
          <tr>
            <td>{{ $r['no_control'] }}</td>
            <td>{{ $r['alumno'] }}</td>
            <td>{{ $r['semestre'] }}</td>
            <td>{{ $r['inscripciones_en_periodo'] }}</td>
            <td>{{ $r['especiales'] }}</td>
            <td>{{ $r['repite'] }}</td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-muted">Sin alumnos.</td></tr>
        @endforelse
      </tbody>
    </table>
    <a href="{{ route('reportes.carrera_periodo.pdf', [
        'carrera' => $carrera,
        'anio'    => $anio,      {{-- <-- estos DOS vienen del controller --}}
        'periodo' => $periodo,   {{-- <-- 'Enero-Junio' o 'Agosto-Diciembre' --}}
        ]) }}"
      class="btn btn-outline-danger mt-3"
      target="_blank">
      Descargar PDF
    </a>
  </div>

  <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary mt-3">← Volver a Reportes</a>
</div>
@endsection
