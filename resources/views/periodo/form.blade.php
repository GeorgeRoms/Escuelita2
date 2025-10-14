<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="anio" class="form-label">{{ __('Año') }}</label>
            <input type="text" name="anio" class="form-control @error('anio') is-invalid @enderror" value="{{ old('anio', $periodo?->anio) }}" id="anio" placeholder="ej: 2025">
            {!! $errors->first('anio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">Periodo</label>
            @php $sel = old('nombre', $periodo->nombre ?? ''); @endphp
            <select name="nombre" id="nombre"
              class="form-select @error('nombre') is-invalid @enderror">
            <option value="">{{ __('Selecciona…') }}</option>
            <option value="Enero-Junio"     @selected($sel === 'Enero-Junio')>Enero-Junio</option>
            <option value="Agosto-Diciembre"@selected($sel === 'Agosto-Diciembre')>Agosto-Diciembre</option>
            </select>
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>