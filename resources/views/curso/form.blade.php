<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_curso" class="form-label">{{ __('Id Curso') }}</label>
            <input type="text" name="id_curso" class="form-control @error('id_curso') is-invalid @enderror" value="{{ old('id_curso', $curso?->id_curso) }}" id="id_curso" placeholder="Id Curso">
            {!! $errors->first('id_curso', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cupo" class="form-label">{{ __('Cupo') }}</label>
            <input type="text" name="cupo" class="form-control @error('cupo') is-invalid @enderror" value="{{ old('cupo', $curso?->cupo) }}" id="cupo" placeholder="Cupo">
            {!! $errors->first('cupo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_materia" class="form-label">{{ __('Fk Materia') }}</label>
            <input type="text" name="fk_materia" class="form-control @error('fk_materia') is-invalid @enderror" value="{{ old('fk_materia', $curso?->fk_materia) }}" id="fk_materia" placeholder="Fk Materia">
            {!! $errors->first('fk_materia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_profesor" class="form-label">{{ __('Fk Profesor') }}</label>
            <input type="text" name="fk_profesor" class="form-control @error('fk_profesor') is-invalid @enderror" value="{{ old('fk_profesor', $curso?->fk_profesor) }}" id="fk_profesor" placeholder="Fk Profesor">
            {!! $errors->first('fk_profesor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_edificio" class="form-label">{{ __('Fk Edificio') }}</label>
            <input type="text" name="fk_edificio" class="form-control @error('fk_edificio') is-invalid @enderror" value="{{ old('fk_edificio', $curso?->fk_edificio) }}" id="fk_edificio" placeholder="Fk Edificio">
            {!! $errors->first('fk_edificio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>