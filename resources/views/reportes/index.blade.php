{{-- resources/views/reportes/index.blade.php --}}
@php
  $selCarrera = $carrera ?? request('carrera');
  $selIntento = $intento ?? request('intento', 'Especial');
@endphp
@extends('layouts.app')

@section('content')
<div class="container">
  <h3 class="mb-3">Reportes</h3>

  <div class="row g-3">
    {{-- Reporte: Alumnos/Materias en “Especial” --}}
    <div class="col-md-6">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Alumnos con materias en “Normal/Repite/Especial” </h5>
          <p class="text-muted mb-3">Resumen por carrera usando el procedimiento almacenado.</p>

          <form action="{{ route('reportes.especial') }}" method="get" class="row g-2 align-items-end">
  <div class="col-md-5">
    <label class="form-label">Carrera</label>
    <select name="carrera" class="form-select" required>
      <option value="" disabled {{ $selCarrera ? '' : 'selected' }}>— elige una carrera —</option>
      @foreach(($carreras ?? []) as $car)
        <option value="{{ $car }}" {{ $selCarrera === $car ? 'selected' : '' }}>
          {{ $car }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-5">
    <label class="form-label">Intento</label>
    <select name="intento" class="form-select" required>
      <option value="" disabled {{ $selIntento ? '' : 'selected' }}>— Intento —</option>
      @foreach(['Normal','Repite','Especial'] as $opt)
        <option value="{{ $opt }}" {{ $selIntento === $opt ? 'selected' : '' }}>
          {{ $opt }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-2">
    <label class="form-label d-none d-md-block">&nbsp;</label>
    <button class="btn btn-primary w-100" type="submit">Ver</button>
  </div>
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

      <form action="{{ route('reportes.profesor.ver') }}" method="get" class="row g-2" id="form-prof-mats"
      data-url-profesores="{{ route('api.areas.profesores', ['area' => '__ID__']) }}">
        <div class="col-12">
          <label class="form-label">Área</label>
          <select name="area_id" id="area_id" class="form-select" required>
  <option value="" disabled selected>— elige un área —</option>
  @foreach($areas ?? [] as $a)
    <option value="{{ $a->id_area }}">{{ $a->nombre_area }}</option>
  @endforeach
</select>
        </div>

        <div class="col-12">
          <label class="form-label">Profesor</label>
          <select name="profesor_id" id="profesor_id" class="form-select" required disabled>
  <option value="" disabled selected>— primero elige un área —</option>
</select>
        </div>

        <div class="col-12">
          <label class="form-label">Periodo</label>
          <select name="periodo_id" class="form-select">
            <option value="">— todos —</option>
            @foreach($periodos ?? [] as $per)
              <option value="{{ $per->id }}">{{ $per->etiqueta }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <button class="btn btn-primary w-100" type="submit" id="btnVer">Ver</button>
        </div>
      </form>

      <script>
(function(){
  const form    = document.getElementById('form-prof-mats');
  const urlTpl  = form.dataset.urlProfesores;
  const areaSel = form.querySelector('#area_id');
  const profSel = form.querySelector('#profesor_id');
  const btnVer  = form.querySelector('#btnVer');

  function updateBtn(){ if(btnVer) btnVer.disabled = !(areaSel.value && profSel.value); }
  updateBtn();

  areaSel.addEventListener('change', async function(){
    const areaId = this.value;
    profSel.innerHTML = '<option value="" disabled selected>Cargando…</option>';
    profSel.disabled = true;
    updateBtn();

    if(!areaId){
      profSel.innerHTML = '<option value="" disabled selected>— primero elige un área —</option>';
      return;
    }

    try {
      const url = urlTpl.replace('__ID__', areaId);
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if(!res.ok) throw new Error('HTTP '+res.status);
      const data = await res.json();

      profSel.innerHTML = '<option value="" disabled selected>— elige un profesor —</option>';
      data.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.textContent = p.nombre;
        profSel.appendChild(opt);
      });
      profSel.disabled = false;
    } catch (e) {
      console.error('Error cargando profesores:', e);
      profSel.innerHTML = '<option value="" disabled selected>Error al cargar</option>';
      profSel.disabled = true;
      alert('No se pudieron cargar los profesores del área seleccionada.');
    } finally {
      updateBtn();
    }
  });

  profSel.addEventListener('change', updateBtn);
})();
</script>

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
        <input
        type="text"
        class="form-control"
        id="numero_control_autocomplete" 
        name="no_control"
        placeholder="Escribe el Número de Control (Ej: 202510001)"
        required
        maxlength="9" {{-- REQUERIDO: Limita a 9 caracteres (el número de control) --}}
        pattern="[0-9]{9}" {{-- OPCIONAL: Asegura que sean 9 dígitos exactos --}}
        >
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



{{-- Reporte: Top 10 promedios por carrera --}}
<div class="col-md-6">
  <div class="card h-100 shadow-sm">
    <div class="card-body">
      <h5 class="card-title">Top 10 alumnos por promedio (por carrera)</h5>
      <p class="text-muted mb-3">Promedio general y desglosado por periodo.</p>

      <form action="{{ route('reportes.top_alumnos.ver') }}" method="get" class="row g-2 align-items-end" id="form-top10">
        <div class="col-md-9">
          <label class="form-label">Carrera</label>
          <select name="carrera_id" class="form-select" required>
            <option value="" disabled selected>— elige una carrera —</option>
            @foreach(($carreras_id ?? []) as $car) {{-- opcional: si ya tienes sólo nombre, ver nota abajo --}}
              <option value="{{ $car->id_carrera }}">{{ $car->nombre_carr }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 d-grid">
          <button class="btn btn-primary" type="submit">Ver</button>
        </div>
      </form>
    </div>
  </div>
</div>


    {{-- Aquí puedes seguir agregando más tarjetas de reportes --}}
  </div>
</div>
@endsection

