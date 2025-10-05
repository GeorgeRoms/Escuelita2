<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="no_control" class="form-label">{{ __('No Control') }}</label>
            <input type="text" name="no_control" class="form-control @error('no_control') is-invalid @enderror" value="{{ old('no_control', $alumno?->no_control) }}" id="no_control" placeholder="No Control">
            {!! $errors->first('no_control', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $alumno?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="apellido_pat" class="form-label">{{ __('Apellido Pat') }}</label>
            <input type="text" name="apellido_pat" class="form-control @error('apellido_pat') is-invalid @enderror" value="{{ old('apellido_pat', $alumno?->apellido_pat) }}" id="apellido_pat" placeholder="Apellido Pat">
            {!! $errors->first('apellido_pat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="apellido_mat" class="form-label">{{ __('Apellido Mat') }}</label>
            <input type="text" name="apellido_mat" class="form-control @error('apellido_mat') is-invalid @enderror" value="{{ old('apellido_mat', $alumno?->apellido_mat) }}" id="apellido_mat" placeholder="Apellido Mat">
            {!! $errors->first('apellido_mat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="genero" class="form-label">{{ __('Genero') }}</label>
            <input type="text" name="genero" class="form-control @error('genero') is-invalid @enderror" value="{{ old('genero', $alumno?->genero) }}" id="genero" placeholder="Genero">
            {!! $errors->first('genero', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_carrera" class="form-label">{{ __('Fk Carrera') }}</label>
            <input type="text" name="fk_carrera" class="form-control @error('fk_carrera') is-invalid @enderror" value="{{ old('fk_carrera', $alumno?->fk_carrera) }}" id="fk_carrera" placeholder="Fk Carrera">
            {!! $errors->first('fk_carrera', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>