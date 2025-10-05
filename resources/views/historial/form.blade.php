<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="fk_alumno" class="form-label">{{ __('Fk Alumno') }}</label>
            <input type="text" name="fk_alumno" class="form-control @error('fk_alumno') is-invalid @enderror" value="{{ old('fk_alumno', $historial?->fk_alumno) }}" id="fk_alumno" placeholder="Fk Alumno">
            {!! $errors->first('fk_alumno', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_apertura" class="form-label">{{ __('Fecha Apertura') }}</label>
            <input type="text" name="fecha_apertura" class="form-control @error('fecha_apertura') is-invalid @enderror" value="{{ old('fecha_apertura', $historial?->fecha_apertura) }}" id="fecha_apertura" placeholder="Fecha Apertura">
            {!! $errors->first('fecha_apertura', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="observaciones" class="form-label">{{ __('Observaciones') }}</label>
            <input type="text" name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" value="{{ old('observaciones', $historial?->observaciones) }}" id="observaciones" placeholder="Observaciones">
            {!! $errors->first('observaciones', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>