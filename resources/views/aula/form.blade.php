<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="edificio_id" class="form-label">{{ __('Edificio Id') }}</label>
            <input type="text" name="edificio_id" class="form-control @error('edificio_id') is-invalid @enderror" value="{{ old('edificio_id', $aula?->edificio_id) }}" id="edificio_id" placeholder="Edificio Id">
            {!! $errors->first('edificio_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="salon" class="form-label">{{ __('Salon') }}</label>
            <input type="text" name="salon" class="form-control @error('salon') is-invalid @enderror" value="{{ old('salon', $aula?->salon) }}" id="salon" placeholder="Salon">
            {!! $errors->first('salon', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>