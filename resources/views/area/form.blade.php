<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_area" class="form-label">{{ __('Id Area') }}</label>
            <input type="text" name="id_area" class="form-control @error('id_area') is-invalid @enderror" value="{{ old('id_area', $area?->id_area) }}" id="id_area" placeholder="Id Area">
            {!! $errors->first('id_area', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre_area" class="form-label">{{ __('Nombre Area') }}</label>
            <input type="text" name="nombre_area" class="form-control @error('nombre_area') is-invalid @enderror" value="{{ old('nombre_area', $area?->nombre_area) }}" id="nombre_area" placeholder="Nombre Area">
            {!! $errors->first('nombre_area', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_edificio" class="form-label">{{ __('Fk Edificio') }}</label>
            <input type="text" name="fk_edificio" class="form-control @error('fk_edificio') is-invalid @enderror" value="{{ old('fk_edificio', $area?->fk_edificio) }}" id="fk_edificio" placeholder="Fk Edificio">
            {!! $errors->first('fk_edificio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_jefe" class="form-label">{{ __('Fk Jefe') }}</label>
            <input type="text" name="fk_jefe" class="form-control @error('fk_jefe') is-invalid @enderror" value="{{ old('fk_jefe', $area?->fk_jefe) }}" id="fk_jefe" placeholder="Fk Jefe">
            {!! $errors->first('fk_jefe', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>