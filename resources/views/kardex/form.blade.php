<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_kardex" class="form-label">{{ __('Id Kardex') }}</label>
            <input type="text" name="id_kardex" class="form-control @error('id_kardex') is-invalid @enderror" value="{{ old('id_kardex', $kardex?->id_kardex) }}" id="id_kardex" placeholder="Id Kardex">
            {!! $errors->first('id_kardex', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_alumno" class="form-label">{{ __('Fk Alumno') }}</label>
            <input type="text" name="fk_alumno" class="form-control @error('fk_alumno') is-invalid @enderror" value="{{ old('fk_alumno', $kardex?->fk_alumno) }}" id="fk_alumno" placeholder="Fk Alumno">
            {!! $errors->first('fk_alumno', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_curso" class="form-label">{{ __('Fk Curso') }}</label>
            <input type="text" name="fk_curso" class="form-control @error('fk_curso') is-invalid @enderror" value="{{ old('fk_curso', $kardex?->fk_curso) }}" id="fk_curso" placeholder="Fk Curso">
            {!! $errors->first('fk_curso', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_inscri" class="form-label">{{ __('Fecha Inscri') }}</label>
            <input type="text" name="fecha_inscri" class="form-control @error('fecha_inscri') is-invalid @enderror" value="{{ old('fecha_inscri', $kardex?->fecha_inscri) }}" id="fecha_inscri" placeholder="Fecha Inscri">
            {!! $errors->first('fecha_inscri', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $kardex?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="promedio" class="form-label">{{ __('Promedio') }}</label>
            <input type="text" name="promedio" class="form-control @error('promedio') is-invalid @enderror" value="{{ old('promedio', $kardex?->promedio) }}" id="promedio" placeholder="Promedio">
            {!! $errors->first('promedio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="oportunidad" class="form-label">{{ __('Oportunidad') }}</label>
            <input type="text" name="oportunidad" class="form-control @error('oportunidad') is-invalid @enderror" value="{{ old('oportunidad', $kardex?->oportunidad) }}" id="oportunidad" placeholder="Oportunidad">
            {!! $errors->first('oportunidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="intento" class="form-label">{{ __('Intento') }}</label>
            <input type="text" name="intento" class="form-control @error('intento') is-invalid @enderror" value="{{ old('intento', $kardex?->intento) }}" id="intento" placeholder="Intento">
            {!! $errors->first('intento', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="semestre" class="form-label">{{ __('Semestre') }}</label>
            <input type="text" name="semestre" class="form-control @error('semestre') is-invalid @enderror" value="{{ old('semestre', $kardex?->semestre) }}" id="semestre" placeholder="Semestre">
            {!! $errors->first('semestre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="unidades_reprobadas" class="form-label">{{ __('Unidades Reprobadas') }}</label>
            <input type="text" name="unidades_reprobadas" class="form-control @error('unidades_reprobadas') is-invalid @enderror" value="{{ old('unidades_reprobadas', $kardex?->unidades_reprobadas) }}" id="unidades_reprobadas" placeholder="Unidades Reprobadas">
            {!! $errors->first('unidades_reprobadas', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>