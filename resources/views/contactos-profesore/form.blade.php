<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- <div class="form-group mb-2 mb20">
            <label for="id_contacto" class="form-label">{{ __('Id Contacto') }}</label>
            <input type="text" name="id_contacto" class="form-control @error('id_contacto') is-invalid @enderror" value="{{ old('id_contacto', $contactosProfesore?->id_contacto) }}" id="id_contacto" placeholder="Id Contacto">
            {!! $errors->first('id_contacto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Correo</label>
    <input type="email" name="correo" class="form-control"
           value="{{ old('correo', $contacto?->correo ?? '') }}">
    {!! $errors->first('correo', '<div class="invalid-feedback d-block"><strong>:message</strong></div>') !!}
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Teléfono</label>
    <input type="text" name="telefono" class="form-control"
           value="{{ old('telefono', $contacto?->telefono ?? '') }}">
  </div>
  <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Calle</label>
            <input type="text" name="calle" class="form-control"
                   value="{{ old('calle', $contacto?->calle ?? '') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Colonia</label>
            <input type="text" name="colonia" class="form-control"
                   value="{{ old('colonia', $contacto?->colonia ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Número exterior</label>
            <input type="text" name="num_ext" class="form-control"
                   value="{{ old('num_ext', $contacto?->num_ext ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Número interior (opcional)</label>
            <input type="text" name="num_int" class="form-control"
                   value="{{ old('num_int', $contacto?->num_int ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Código postal</label>
            <input type="text" name="cp" class="form-control"
                   value="{{ old('cp', $contacto?->cp ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control"
                   value="{{ old('estado', $contacto?->estado ?? '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">País</label>
            <input type="text" name="pais" class="form-control"
                   value="{{ old('pais', $contacto?->pais ?? '') }}">
        </div>
    </div>
</div>

        <div class="form-group mb-2 mb20">
            <label class="form-label">Profesor</label>
            <select name="fk_profesor" class="form-select @error('fk_profesor') is-invalid @enderror">
            <option value="">— Selecciona —</option>
            @foreach($profesores as $id => $nom)
            <option value="{{ $id }}" @selected(old('fk_profesor', $contacto?->fk_profesor)==$id)>{{ $nom }}</option>
            @endforeach
            </select>
            {!! $errors->first('fk_profesor','<div class="invalid-feedback">:message</div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>