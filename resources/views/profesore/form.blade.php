<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- <div class="form-group mb-2 mb20">
            <label for="id_profesor" class="form-label">{{ __('Id Profesor') }}</label>
            <input type="text" name="id_profesor" class="form-control @error('id_profesor') is-invalid @enderror" value="{{ old('id_profesor', $profesore?->id_profesor) }}" id="id_profesor" placeholder="Id Profesor">
            {!! $errors->first('id_profesor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $profesore?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="apellido_pat" class="form-label">{{ __('Apellido Paterno') }}</label>
            <input type="text" name="apellido_pat" class="form-control @error('apellido_pat') is-invalid @enderror" value="{{ old('apellido_pat', $profesore?->apellido_pat) }}" id="apellido_pat" placeholder="Paterno">
            {!! $errors->first('apellido_pat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="apellido_mat" class="form-label">{{ __('Apellido Materno') }}</label>
            <input type="text" name="apellido_mat" class="form-control @error('apellido_mat') is-invalid @enderror" value="{{ old('apellido_mat', $profesore?->apellido_mat) }}" id="apellido_mat" placeholder="Materno">
            {!! $errors->first('apellido_mat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="area" class="form-label">{{ __('Área') }}</label>
            <select name="fk_area" id="fk_area" class="form-select @error('fk_area') is-invalid @enderror" required>
            <option value="">Seleccione...</option>
            @foreach($catalAreas as $id => $nom)
            <option value="{{ $id }}" @selected(old('fk_area', $profesore?->fk_area) == $id)>{{ $nom }}</option>
            @endforeach
            </select>
            {!! $errors->first('fk_area', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>


        <div class="form-group mb-2 mb20">
            <label for="tipo" class="form-label">{{ __('Tipo') }}</label>
            @php
            $opciones = ['Tiempo completo', 'Medio Tiempo', 'Asignatura'];
            $sel = old('tipo', $profesore?->tipo);
            @endphp
            <select name="tipo" id="tipo"
                class="form-select @error('tipo') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach ($opciones as $n)
            <option value="{{ $n }}" @selected((string)$sel === (string)$n)>{{ $n }}</option>
            @endforeach
            </select>
            {!! $errors->first('creditos', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>