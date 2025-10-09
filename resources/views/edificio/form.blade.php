<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="edificio" class="form-label">{{ __('Edificio') }}</label>
            <input type="text" name="edificio" class="form-control @error('edificio') is-invalid @enderror" value="{{ old('edificio', $edificio?->edificio) }}" id="edificio" placeholder="Edificio">
            {!! $errors->first('edificio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="salon" class="form-label">{{ __('Salón') }}</label>
            <input type="text" name="salon" class="form-control @error('salon') is-invalid @enderror" value="{{ old('salon', $edificio?->salon) }}" id="salon" placeholder="Salón">
            {!! $errors->first('salon', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>