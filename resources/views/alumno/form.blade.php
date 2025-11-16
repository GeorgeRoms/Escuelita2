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
      <label for="carrera_id" class="form-label">Carrera</label>
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


    <div class="col-md-3">
  <label class="form-label">Semestre</label>
  <select name="semestre" class="form-select" required>
    @php $sSel = old('semestre', $alumno?->semestre); @endphp
    <option value="">Seleccione…</option>
    @for($s=1; $s<=20; $s++)
      <option value="{{ $s }}" @selected($sSel==$s)>{{ $s }}</option>
    @endfor
  </select>
  {!! $errors->first('semestre', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
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

    <hr>
{{-- Encabezado con botón para enroscar / desenroscar --}}
<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Datos de contacto</h5>

    <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center"
            type="button"
            data-bs-toggle="collapse"          {{-- si usas Bootstrap 4 cambia a: data-toggle="collapse" --}}
            data-bs-target="#contactoCollapse"
            aria-expanded="false"
            aria-controls="contactoCollapse">
        <span class="me-1" id="contactoCollapseText">Mostrar</span>
        <i class="bi bi-chevron-down" id="contactoCollapseIcon"></i>
    </button>
</div>

<div class="collapse" id="contactoCollapse">
  <div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Correo</label>
    <input type="email" name="correo" class="form-control"
           value="{{ old('correo', $alumno->contacto->correo ?? '') }}">
    {!! $errors->first('correo', '<div class="invalid-feedback d-block"><strong>:message</strong></div>') !!}
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Teléfono</label>
    <input type="text" name="telefono" class="form-control"
           value="{{ old('telefono', $alumno->contacto->telefono ?? '') }}">
  </div>
  <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Calle</label>
            <input type="text" name="calle" class="form-control"
                   value="{{ old('calle', $alumno->contacto?->calle ?? '') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Colonia</label>
            <input type="text" name="colonia" class="form-control"
                   value="{{ old('colonia', $alumno->contacto?->colonia ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Número exterior</label>
            <input type="text" name="num_ext" class="form-control"
                   value="{{ old('num_ext', $alumno->contacto?->num_ext ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Número interior (opcional)</label>
            <input type="text" name="num_int" class="form-control"
                   value="{{ old('num_int', $alumno->contacto?->num_int ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Código postal</label>
            <input type="text" name="cp" class="form-control"
                   value="{{ old('cp', $alumno->contacto?->cp ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control"
                   value="{{ old('estado', $alumno->contacto?->estado ?? '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">País</label>
            <input type="text" name="pais" class="form-control"
                   value="{{ old('pais', $alumno->contacto?->pais ?? '') }}">
        </div>
    </div>
</div>
</div>

  </div>

  <div class="col-md-12 mt20 mt-2">
    <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
  </div>
</div>
