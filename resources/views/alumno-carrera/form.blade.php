@php
  // Soportar nombres que puedes usar en distintas vistas
  $row = $asignacion ?? $alumnoCarrera ?? null;

  // Catálogos seguros
  $alumnos  = $alumnos  ?? collect();
  $carreras = $carreras ?? collect();

  // Datos del alumno para mostrar en modo “solo lectura”
  $noCtrl = old('alumno_no_control', $row?->alumno_no_control ?? ($alumno->no_control ?? ''));
  $alObj  = $row?->alumno ?? ($alumno ?? null);
  $alNom  = $alObj ? trim($alObj->nombre.' '.$alObj->apellido_pat.' '.($alObj->apellido_mat ?? '')) : '';
@endphp

<div class="row padding-1 p-1">
  <div class="col-md-12">

    {{-- ALUMNO: solo lectura si viene fijado; de lo contrario, select --}}
    @if ($noCtrl)
      <div class="form-group mb-2 mb20">
        <label class="form-label">Alumno</label>
        <input type="text" class="form-control" value="{{ $noCtrl }}{{ $alNom ? ' — '.$alNom : '' }}" disabled>
        <input type="hidden" name="alumno_no_control" value="{{ $noCtrl }}">
        @error('alumno_no_control') <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div> @enderror
      </div>
    @else
      <div class="form-group mb-2 mb20">
        <label class="form-label">Alumno</label>
        <select name="alumno_no_control" class="form-select @error('alumno_no_control') is-invalid @enderror">
          <option value="">Seleccione…</option>
          @foreach($alumnos as $no => $label)
            <option value="{{ $no }}" @selected(old('alumno_no_control', $row?->alumno_no_control) == $no)>{{ $label }}</option>
          @endforeach
        </select>
        @error('alumno_no_control') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
      </div>
    @endif

    {{-- CARRERA --}}
    <div class="form-group mb-2 mb20">
      <label class="form-label">Carrera</label>
      <select name="carrera_id" class="form-select @error('carrera_id') is-invalid @enderror">
        <option value="">Seleccione…</option>
        @foreach($carreras as $id => $nombre)
          <option value="{{ $id }}" @selected(old('carrera_id', $row?->carrera_id) == $id)>{{ $nombre }}</option>
        @endforeach
      </select>
      @error('carrera_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
    </div>

    {{-- ESTATUS --}}
    <div class="form-group mb-2 mb20">
      <label class="form-label">Estatus</label>
      @php $st = old('estatus', $row?->estatus ?? 'Activo'); @endphp
      <select name="estatus" class="form-select @error('estatus') is-invalid @enderror">
        <option value="Activo" @selected($st==='Activo')>Activo</option>
        <option value="Baja"   @selected($st==='Baja')>Baja</option>
      </select>
      @error('estatus') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
    </div>

    {{-- FECHAS --}}
    <div class="form-group mb-2 mb20">
      <label class="form-label">Fecha inicio</label>
      <input type="date" name="fecha_inicio"
             class="form-control @error('fecha_inicio') is-invalid @enderror"
             value="{{ old('fecha_inicio', $row?->fecha_inicio ?? now()->toDateString()) }}">
      @error('fecha_inicio') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
    </div>

    <div class="form-group mb-2 mb20">
      <label class="form-label">Fecha fin</label>
      <input type="date" name="fecha_fin"
             class="form-control @error('fecha_fin') is-invalid @enderror"
             value="{{ old('fecha_fin', $row?->fecha_fin ?? '') }}">
      @error('fecha_fin') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
    </div>

  </div>

  <div class="col-md-12 mt20 mt-2">
    <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
  </div>
</div>
