<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- <div class="form-group mb-2 mb20">
            <label for="id_contacto" class="form-label">{{ __('Id Contacto') }}</label>
            <input type="text" name="id_contacto" class="form-control @error('id_contacto') is-invalid @enderror" value="{{ old('id_contacto', $contactosAlumno?->id_contacto) }}" id="id_contacto" placeholder="Id Contacto">
            {!! $errors->first('id_contacto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="correo" class="form-label">{{ __('Correo') }}</label>
            <input type="text" name="correo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo', $contactosAlumno?->correo) }}" id="correo" placeholder="Correo">
            {!! $errors->first('correo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="telefono" class="form-label">{{ __('Telefono') }}</label>
            <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono', $contactosAlumno?->telefono) }}" id="telefono" placeholder="Telefono">
            {!! $errors->first('telefono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="direccion" class="form-label">{{ __('Direccion') }}</label>
            <input type="text" name="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $contactosAlumno?->direccion) }}" id="direccion" placeholder="Direccion">
            {!! $errors->first('direccion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
             <label for="fk_alumno" class="form-label">Alumno</label>
             <select name="fk_alumno" id="fk_alumno"
             class="form-select @error('fk_alumno') is-invalid @enderror" required>
             <option value="">Selecciona…</option>
            @foreach($alumnos as $nc => $nombre)
            <option value="{{ $nc }}" @selected(old('fk_alumno', $contactosAlumno?->fk_alumno) == $nc)>{{ $nombre }}</option>
            @endforeach
            </select>
            {!! $errors->first('fk_alumno','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>