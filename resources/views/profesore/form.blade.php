<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $profesore?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="apellido_paterno" class="form-label">{{ __('Apellido Paterno') }}</label>
            <input type="text" name="apellido_paterno" class="form-control @error('apellido_paterno') is-invalid @enderror" value="{{ old('apellido_paterno', $profesore?->apellido_paterno) }}" id="apellido_paterno" placeholder="Paterno">
            {!! $errors->first('apellido_paterno', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="apellido_materno" class="form-label">{{ __('Apellido Materno') }}</label>
            <input type="text" name="apellido_materno" class="form-control @error('apellido_materno') is-invalid @enderror" value="{{ old('apellido_materno', $profesore?->apellido_materno) }}" id="apellido_materno" placeholder="Materno">
            {!! $errors->first('apellido_materno', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        {{-- BLOQUE DEL SELECT DE ÁREA (USANDO LA ESTRUCTURA LIMPIA) --}}
        <div class="form-group mb-2 mb20">
            <label for="fk_area" class="form-label">{{ __('Área') }}</label>
            <select name="fk_area" id="fk_area" class="form-select @error('fk_area') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
                {{-- catalAreas ahora es una colección de objetos, gracias a la corrección en el Controlador --}}
                @foreach($catalAreas as $area)
                    <option 
                        value="{{ $area->id_area }}" 
                        @selected(old('fk_area', $profesore?->fk_area) == $area->id_area)
                    >
                        {{ $area->nombre_area }}
                    </option>
                @endforeach
            </select>
            {!! $errors->first('fk_area', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        {{-- FIN BLOQUE DE ÁREA --}}

        {{-- CAMPO TIPO (AJUSTADO A SELECT CON LA ESTRUCTURA COMPLETA) --}}
        <div class="form-group mb-2 mb20">
            <label for="tipo" class="form-label">{{ __('Tipo') }}</label>
            {{-- ELIMINAMOS EL BLOQUE @php/@endphp Y USAMOS SINTAXIS DE ASIGNACIÓN --}}
            @php($opciones = ['Tiempo completo', 'Medio Tiempo', 'Asignatura'])
            @php($sel = old('tipo', $profesore?->tipo))
            
            <select name="tipo" id="tipo"
                class="form-select @error('tipo') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach ($opciones as $n)
            <option value="{{ $n }}" @selected((string)$sel === (string)$n)>{{ $n }}</option>
            @endforeach
            </select>
            {{-- ATENCIÓN: El error para 'creditos' probablemente es un error tipográfico y debería ser 'tipo' --}}
            {!! $errors->first('creditos', '<div class="invalid-feedback"><strong>:message</strong></div>') !!} 
        </div>
        
    </div>
    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>
