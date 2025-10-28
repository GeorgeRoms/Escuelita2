<div class="row padding-1 p-1">
  <div class="col-md-12">

    {{-- MENSAJE DE ERROR DEL TRIGGER (SOLAPAMIENTO) --}}
    @if($errors->has('horario'))
      <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-x-octagon-fill me-2"></i>
        <div><strong>Error de Horario:</strong> {{ $errors->first('horario') }}</div>
      </div>
    @endif

    <div class="form-group mb-2 mb20">
      <label for="cupo" class="form-label">{{ __('Cupo') }}</label>
      <input type="text" name="cupo" class="form-control @error('cupo') is-invalid @enderror"
             value="{{ old('cupo', $curso?->cupo) }}" id="cupo" placeholder="Cupo">
      {!! $errors->first('cupo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="form-group mb-2 mb20">
      <label for="fk_materia" class="form-label">{{ __('Materia') }}</label>
      <select name="fk_materia" id="fk_materia"
              class="form-select @error('fk_materia') is-invalid @enderror" required>
        <option value="">Selecciona…</option>
        @foreach($materias as $id => $nombre)
          <option value="{{ $id }}" @selected(old('fk_materia', $curso?->fk_materia) == $id)>{{ $nombre }}</option>
        @endforeach
      </select>
      {!! $errors->first('fk_materia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="form-group mb-2 mb20">
      <label for="fk_profesor" class="form-label">{{ __('Profesor') }}</label>
      <select name="fk_profesor" id="fk_profesor"
              class="form-select @error('fk_profesor') is-invalid @enderror" required>
        <option value="">Selecciona…</option>
        @foreach($profesores as $id => $nombre)
          <option value="{{ $id }}" @selected(old('fk_profesor', $curso?->fk_profesor) == $id)>{{ $nombre }}</option>
        @endforeach
      </select>
      {!! $errors->first('fk_profesor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="form-group mb-2 mb20">
      <label for="aula_id" class="form-label">{{ __('Edificio - Aula') }}</label>
      <select name="aula_id" id="aula_id"
              class="form-select @error('aula_id') is-invalid @enderror">
        <option value="">— Sin aula asignada —</option>
        @foreach($aulas as $id => $label)
          <option value="{{ $id }}" @selected(old('aula_id', $curso->aula_id ?? null) == $id)>{{ $label }}</option>
        @endforeach
      </select>
      @error('aula_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mb-2 mb20">
      <label class="form-label">Periodo</label>
      <select name="periodo_id" class="form-select @error('periodo_id') is-invalid @enderror" required>
        <option value="">— Selecciona periodo —</option>
        @foreach($periodos as $id => $label)
          <option value="{{ $id }}" @selected(old('periodo_id', $curso->periodo_id ?? null) == $id)>{{ $label }}</option>
        @endforeach
      </select>
      @error('periodo_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- ======= NUEVOS CAMPOS: HORARIO ======= --}}
    @php
      $diasListado = $dias ?? ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
    @endphp

    <div class="form-group mb-2 mb20">
      <label for="dia_semana" class="form-label">Día de la semana</label>
      <select name="dia_semana" id="dia_semana"
              class="form-select @error('dia_semana') is-invalid @enderror" required>
        <option value="">-- Selecciona --</option>
        @foreach($diasListado as $d)
          <option value="{{ $d }}" @selected(old('dia_semana', $curso->dia_semana ?? '') === $d)>{{ $d }}</option>
        @endforeach
      </select>
      @error('dia_semana') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mb-2 mb20">
      <label for="hora_inicio" class="form-label">Hora de inicio</label>
      <input type="time" name="hora_inicio" id="hora_inicio"
             value="{{ old('hora_inicio', optional($curso->hora_inicio ?? null)->format('H:i')) }}"
             class="form-control @error('hora_inicio') is-invalid @enderror" required>
      @error('hora_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mb-3 mb20">
      <label for="hora_fin" class="form-label">Hora de fin</label>
      <input type="time" name="hora_fin" id="hora_fin"
             value="{{ old('hora_fin', optional($curso->hora_fin ?? null)->format('H:i')) }}"
             class="form-control @error('hora_fin') is-invalid @enderror" required>
      @error('hora_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    {{-- ======= /NUEVOS CAMPOS ======= --}}

  </div>

  <div class="col-md-12 mt20 mt-2">
    <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
  </div>
</div>
