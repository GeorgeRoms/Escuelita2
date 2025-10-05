<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_materia" class="form-label">{{ __('Id Materia') }}</label>
            <input type="text" name="id_materia" class="form-control @error('id_materia') is-invalid @enderror" value="{{ old('id_materia', $materia?->id_materia) }}" id="id_materia" placeholder="Id Materia">
            {!! $errors->first('id_materia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre_mat" class="form-label">{{ __('Nombre Mat') }}</label>
            <input type="text" name="nombre_mat" class="form-control @error('nombre_mat') is-invalid @enderror" value="{{ old('nombre_mat', $materia?->nombre_mat) }}" id="nombre_mat" placeholder="Nombre Mat">
            {!! $errors->first('nombre_mat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="creditos" class="form-label">{{ __('Creditos') }}</label>
            <input type="text" name="creditos" class="form-control @error('creditos') is-invalid @enderror" value="{{ old('creditos', $materia?->creditos) }}" id="creditos" placeholder="Creditos">
            {!! $errors->first('creditos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_cadena" class="form-label">{{ __('Fk Cadena') }}</label>
            <input type="text" name="fk_cadena" class="form-control @error('fk_cadena') is-invalid @enderror" value="{{ old('fk_cadena', $materia?->fk_cadena) }}" id="fk_cadena" placeholder="Fk Cadena">
            {!! $errors->first('fk_cadena', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>