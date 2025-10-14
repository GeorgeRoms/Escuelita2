<div class="row padding-1 p-1">
  <div class="col-md-12">

    {{-- Si capturas manual el no_control, descomenta este bloque --}}
    {{-- 
    <div class="form-group mb-2 mb20">
      <label for="no_control" class="form-label">{{ __('No Control') }}</label>
      <input type="text" name="no_control" class="form-control @error('no_control') is-invalid @enderror"
             value="{{ old('no_control', $alumno?->no_control) }}" id="no_control" placeholder="No Control">
      {!! $errors->first('no_control', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>
    --}}

    <div class="form-group mb-2 mb20">
      <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
      <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
             value="{{ old('nombre', $alumno?->nombre) }}" id="nombre" placeholder="Nombre(s)">
      {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="form-group mb-2 mb20">
      <label for="apellido_pat" class="form-label">{{ __('Apellido Paterno') }}</label>
      <input type="text" name="apellido_pat" class="form-control @error('apellido_pat') is-invalid @enderror"
             value="{{ old('apellido_pat', $alumno?->apellido_pat) }}" id="apellido_pat" placeholder="Paterno">
      {!! $errors->first('apellido_pat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="form-group mb-2 mb20">
      <label for="apellido_mat" class="form-label">{{ __('Apellido Materno') }}</label>
      <input type="text" name="apellido_mat" class="form-control @error('apellido_mat') is-invalid @enderror"
             value="{{ old('apellido_mat', $alumno?->apellido_mat) }}" id="apellido_mat" placeholder="Materno">
      {!! $errors->first('apellido_mat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="form-group mb-2">
      <label for="genero" class="form-label">Género</label>
      <select name="genero" id="genero" class="form-select @error('genero') is-invalid @enderror">
        @php $gSel = old('genero', $alumno?->genero); @endphp
        <option value="">Seleccione...</option>
        <option value="M" @selected($gSel === 'M')>M</option>
        <option value="F" @selected($gSel === 'F')>F</option>
      </select>
      {!! $errors->first('genero', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
    </div>

    {{-- Carrera (opcional) --> se guarda en la pivot alumno_carrera --}}
    <div class="form-group mb-2">
      <label for="carrera_id" class="form-label">Carrera (opcional)</label>
      @php
        // Para edición: pásame $carreraActualId desde el controlador (id_carrera) o calcula el primero
        $carreraActualId = old('carrera_id', $carreraActualId ?? null);
      @endphp
      <select name="carrera_id" id="carrera_id" class="form-select @error('carrera_id') is-invalid @enderror">
        <option value="">Seleccione...</option>
        @foreach($carreras as $id => $nombre)
          <option value="{{ $id }}" @selected(old('carrera_id', $carreraActualId) == $id)>{{ $nombre }}</option>
        @endforeach
      </select>
      {!! $errors->first('carrera_id', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
    </div>

    <div class="row" style="margin-bottom: 1%">
      <div class="col-md-3">
        <label class="form-label">Año</label>
        <input type="number" name="anio" class="form-control"
               value="{{ old('anio', $alumno?->anio ?? now()->year) }}" min="2000">
      </div>
      <div class="col-md-3">
        <label class="form-label">Periodo</label>
        <select name="periodo" class="form-select">
          @php $pSel = old('periodo', $alumno?->periodo); @endphp
          <option value="1" @selected($pSel==1)>Enero-Junio</option>
          <option value="2" @selected($pSel==2)>Agosto-Diciembre</option>
        </select>
      </div>
    </div>

  </div>

  <div class="col-md-12 mt20 mt-2">
    <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
  </div>
</div>
