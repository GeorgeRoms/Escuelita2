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
            <label for="fk_profesor" class="form-label">{{ __('Fk Profesor') }}</label>
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
            <label for="fk_edificio" class="form-label">{{ __('Fk Edificio') }}</label>
            <select name="fk_edificio" id="fk_edificio"
              class="form-select @error('fk_edificio') is-invalid @enderror" required>
            <option value="">Selecciona…</option>
            @foreach($edificios as $id => $label)
            <option value="{{ $id }}" @selected(old('fk_edificio', $curso?->fk_edificio) == $id)>
            {{ $label }}
            </option>
            @endforeach
            </select>
            {!! $errors->first('fk_edificio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>