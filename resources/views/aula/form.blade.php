<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label class="form-label">Edificio</label>
            <select name="edificio_id" class="form-select @error('edificio_id') is-invalid @enderror">
            <option value="">Seleccione...</option>
            @foreach($edificios as $id => $label)
            <option value="{{ $id }}" @selected(old('edificio_id', $aula->edificio_id ?? null) == $id)>
            {{ $label }}
            </option>
            @endforeach
            </select>
            @error('edificio_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group mb-2 mb20">
            <label class="form-label">Aula / Salón</label>
            <input type="text" name="salon" class="form-control @error('salon') is-invalid @enderror"
             value="{{ old('salon', $aula->salon ?? '') }}" placeholder="101, B-12, etc.">
            @error('salon') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>