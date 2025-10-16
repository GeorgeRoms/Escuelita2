{{-- resources/views/reportes/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
  <h3 class="mb-3">Reportes</h3>

  <div class="row g-3">
    {{-- Reporte: Alumnos/Materias en “Especial” --}}
    <div class="col-md-6">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Alumnos/Materias en “Especial”</h5>
          <p class="text-muted mb-3">Resumen por carrera usando el procedimiento almacenado.</p>

          <form action="{{ route('reportes.especial') }}" method="get" class="d-flex gap-2">
            <select name="carrera" class="form-select" required>
              <option value="" disabled selected>— elige una carrera —</option>
              @foreach($carreras ?? [] as $car)
                <option value="{{ $car }}">{{ $car }}</option>
              @endforeach
            </select>
            <button class="btn btn-primary" type="submit">Ver</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Reporte: Alumnos por curso (usa SP alumnos_por_curso) --}}
    <div class="col-md-6">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Alumnos por Curso</h5>
          <p class="text-muted mb-3">Lista alumnos (no_control, alumno, semestre, intento, profesor, materia, periodo).</p>

          <form action="{{ route('reportes.curso.ver') }}" method="get" class="d-flex gap-2">
            <select name="curso_id" class="form-select" required>
              <option value="" disabled selected>— elige un curso —</option>
              @foreach($cursos ?? [] as $c)
                <option value="{{ $c->id_curso }}">{{ $c->etiqueta }}</option>
              @endforeach
            </select>
            <button class="btn btn-primary" type="submit">Ver</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Reporte: Materias impartidas por profesor --}}
<div class="col-md-6">
  <div class="card h-100 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Materias impartidas por profesor</h5>
      <p class="text-muted mb-3">Lista cursos del docente y cuántos alumnos inscritos tiene cada uno.</p>

      <form action="{{ route('reportes.profesor.ver') }}" method="get" class="row g-2">
        <div class="col-12">
          <label class="form-label">Profesor</label>
          <select name="profesor_id" class="form-select" required>
            <option value="" disabled selected>— elige un profesor —</option>
            @foreach($profesores ?? [] as $p)
              <option value="{{ $p->id_profesor }}">{{ $p->docente }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Periodo (opcional)</label>
          <select name="periodo_id" class="form-select">
            <option value="">— todos —</option>
            @foreach($periodos ?? [] as $per)
              <option value="{{ $per->id }}">{{ $per->etiqueta }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <button class="btn btn-primary w-100" type="submit">Ver</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Reporte: Historial de un alumno --}}
<div class="col-md-6">
  <div class="card h-100 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Historial de un alumno</h5>
      <p class="text-muted mb-3">Consulta qué materias ha tomado, con qué profesor y en qué periodo.</p>

      <form action="{{ route('reportes.alumno.ver') }}" method="get" class="d-flex gap-2">
        <select name="no_control" class="form-select" required>
          <option value="" disabled selected>— elige un alumno —</option>
          @foreach($alumnos ?? [] as $a)
            <option value="{{ $a->no_control }}">
              {{ $a->no_control }} — {{ $a->nombre_completo }}
            </option>
          @endforeach
        </select>
        <button class="btn btn-primary" type="submit">Ver</button>
      </form>
    </div>
  </div>
</div>


<div class="col-md-6">
  <div class="card h-100 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Resumen por Carrera + Periodo</h5>
      <p class="text-muted mb-3">KPIs, materias, profesores y alumnos del periodo seleccionado.</p>

      <form action="{{ route('reportes.carrera_periodo.ver') }}" method="get" class="row g-2">
        <div class="col-12">
          <label class="form-label">Carrera</label>
          <select name="carrera" class="form-select" required>
            <option value="" disabled selected>— elige una carrera —</option>
            @foreach($carreras ?? [] as $car)
              <option value="{{ $car }}">{{ $car }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Periodo</label>
          <select name="periodo_id" class="form-select" required>
            <option value="" disabled selected>— elige un periodo —</option>
            @foreach($periodos ?? [] as $per)
              <option value="{{ $per->id }}">{{ $per->etiqueta }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <button class="btn btn-primary w-100" type="submit">Ver</button>
        </div>
      </form>
    </div>
  </div>
</div>

    {{-- Aquí puedes seguir agregando más tarjetas de reportes --}}
  </div>
</div>
@endsection

