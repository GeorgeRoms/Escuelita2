@php
  $edificios  = $edificios  ?? collect();
  $profesores = $profesores ?? collect();
@endphp

<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- <div class="form-group mb-2 mb20">
            <label for="id_area" class="form-label">{{ __('Id Area') }}</label>
            <input type="text" name="id_area" class="form-control @error('id_area') is-invalid @enderror" value="{{ old('id_area', $area?->id_area) }}" id="id_area" placeholder="Id Area">
            {!! $errors->first('id_area', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <input type="text" id="nombre_area" name="nombre_area"
             class="form-control @error('nombre_area') is-invalid @enderror"
             value="{{ old('nombre_area', $area->nombre_area ?? '') }}">
            @error('nombre_area') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
        </div>
        <div class="form-group mb-2 mb20">
            <label class="form-label">Edificio</label>
            @if ($edificios->isEmpty())
            <div class="alert alert-warning">
            No hay edificios registrados. <a href="{{ route('edificios.create') }}">Crear edificio</a>
            </div>
            @endif

            <select name="edificio_id" class="form-select @error('edificio_id') is-invalid @enderror">
                <option value="">Selecciona…</option>
                @foreach($edificios as $id => $label)
                <option value="{{ $id }}" @selected(old('edificio_id', $area->edificio_id ?? null) == $id)>
                {{ $label }}
                </option>
                @endforeach
            </select>
            @error('edificio_id') <div class="invalid-feedback"><strong>{{ $message }}</strong></div> @enderror
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_jefe" class="form-label">{{ __('Jefe de área') }}</label>
            <select name="fk_jefe" id="fk_jefe"
            class="form-select @error('fk_jefe') is-invalid @enderror">
            <option value="">{{ __('Selecciona…') }}</option>
            @foreach($profesores as $id => $nombre)
            <option value="{{ $id }}" @selected(old('fk_jefe', $area?->fk_jefe) == $id)>{{ $nombre }}</option>
            @endforeach
            </select>
            {!! $errors->first('fk_jefe','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>