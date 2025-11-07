<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- <div class="form-group mb-2 mb20">
            <label for="id_materia" class="form-label">{{ __('Id Materia') }}</label>
            <input type="text" name="id_materia" class="form-control @error('id_materia') is-invalid @enderror" value="{{ old('id_materia', $materia?->id_materia) }}" id="id_materia" placeholder="Id Materia">
            {!! $errors->first('id_materia', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="nombre_mat" class="form-label">{{ __('Nombre de la Materia') }}</label>
            <input type="text" name="nombre_mat" class="form-control @error('nombre_mat') is-invalid @enderror" value="{{ old('nombre_mat', $materia?->nombre_mat) }}" id="nombre_mat" placeholder="Materia">
            {!! $errors->first('nombre_mat', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="creditos" class="form-label">{{ __('Número de créditos') }}</label>
            @php
            $opciones = [3, 4, 5];
            $sel = old('creditos', $materia?->creditos);
            @endphp
            <select name="creditos" id="creditos"
                class="form-select @error('creditos') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach ($opciones as $n)
            <option value="{{ $n }}" @selected((string)$sel === (string)$n)>{{ $n }}</option>
            @endforeach
            </select>
            {!! $errors->first('creditos', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_cadena" class="form-label">Prerrequisito</label>
            <select name="fk_cadena" id="fk_cadena"
                class="form-select @error('fk_cadena') is-invalid @enderror">
            <option value="">Sin prerrequisito</option>
            @foreach($candidatas as $id => $nombre)
            <option value="{{ $id }}" @selected(old('fk_cadena', $materia?->fk_cadena) == $id)>
            {{ $nombre }}
            </option>s
            @endforeach
            </select>
            {!! $errors->first('fk_cadena', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>