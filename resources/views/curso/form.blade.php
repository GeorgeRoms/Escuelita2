<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- <div class="form-group mb-2 mb20">
            <label for="id_curso" class="form-label">{{ __('Id Curso') }}</label>
            <input type="text" name="id_curso" class="form-control @error('id_curso') is-invalid @enderror" value="{{ old('id_curso', $curso?->id_curso) }}" id="id_curso" placeholder="Id Curso">
            {!! $errors->first('id_curso', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="cupo" class="form-label">{{ __('Cupo') }}</label>
            <input type="text" name="cupo" class="form-control @error('cupo') is-invalid @enderror" value="{{ old('cupo', $curso?->cupo) }}" id="cupo" placeholder="Cupo">
            {!! $errors->first('cupo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_materia" class="form-label">{{ __('Materia') }}</label>
            <select name="fk_materia" id="fk_materia"
              class="form-select @error('fk_materia') is-invalid @enderror" required>
            <option value="">Selecciona…</option>
            @foreach($materias as $id => $nombre)
            <option value="{{ $id }}" @selected(old('fk_materia', $curso?->fk_materia) == $id)>
            {{ $nombre }}
            </option>
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
            <option value="{{ $id }}" @selected(old('fk_profesor', $curso?->fk_profesor) == $id)>
            {{ $nombre }}
            </option>
            @endforeach
            </select>
            {!! $errors->first('fk_profesor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="aula_id" class="form-label">{{ __('Edificio - Aula') }}</label>
            <select name="aula_id" id="aula_id"
              class="form-select @error('aula_id') is-invalid @enderror" required>
            <option value="">— Sin aula asignada —</option>
            @foreach($aulas as $id => $label)
            <option value="{{ $id }}" @selected(old('aula_id', $curso->aula_id ?? null) == $id)>
            {{ $label }}
            </option>
            @endforeach
            </select>
            @error('aula_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group mb-2 mb20">
            <label class="form-label">Periodo</label>
            <select name="periodo_id" class="form-select">
            <option value="">— Sin periodo —</option>
            @foreach($periodos as $id => $label)
            <option value="{{ $id }}" @selected(old('periodo_id', $curso->periodo_id) == $id)>{{ $label }}</option>
            @endforeach
        </select>
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>