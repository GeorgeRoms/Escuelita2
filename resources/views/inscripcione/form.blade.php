<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="alumno_no_control" class="form-label">{{ __('Alumno No Control') }}</label>
            <input type="text" name="alumno_no_control" class="form-control @error('alumno_no_control') is-invalid @enderror" value="{{ old('alumno_no_control', $inscripcione?->alumno_no_control) }}" id="alumno_no_control" placeholder="Alumno No Control">
            {!! $errors->first('alumno_no_control', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="curso_id" class="form-label">{{ __('Curso Id') }}</label>
            <input type="text" name="curso_id" class="form-control @error('curso_id') is-invalid @enderror" value="{{ old('curso_id', $inscripcione?->curso_id) }}" id="curso_id" placeholder="Curso Id">
            {!! $errors->first('curso_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $inscripcione?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="oportunidad" class="form-label">{{ __('Oportunidad') }}</label>
            <input type="text" name="oportunidad" class="form-control @error('oportunidad') is-invalid @enderror" value="{{ old('oportunidad', $inscripcione?->oportunidad) }}" id="oportunidad" placeholder="Oportunidad">
            {!! $errors->first('oportunidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="intento" class="form-label">{{ __('Intento') }}</label>
            <input type="text" name="intento" class="form-control @error('intento') is-invalid @enderror" value="{{ old('intento', $inscripcione?->intento) }}" id="intento" placeholder="Intento">
            {!! $errors->first('intento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="semestre" class="form-label">{{ __('Semestre') }}</label>
            <input type="text" name="semestre" class="form-control @error('semestre') is-invalid @enderror" value="{{ old('semestre', $inscripcione?->semestre) }}" id="semestre" placeholder="Semestre">
            {!! $errors->first('semestre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>