<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="nombre_carr" class="form-label">{{ __('Nombre de la carrera') }}</label>
            <input type="text" name="nombre_carr" class="form-control @error('nombre_carr') is-invalid @enderror" value="{{ old('nombre_carr', $carrera?->nombre_carr) }}" id="nombre_carr" placeholder="Carrera">
            {!! $errors->first('nombre_carr', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="capacidad" class="form-label">{{ __('Cupo') }}</label>
            <input type="text" name="capacidad" class="form-control @error('capacidad') is-invalid @enderror" value="{{ old('capacidad', $carrera?->capacidad) }}" id="capacidad" placeholder="Cupo límite">
            {!! $errors->first('capacidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

         {{-- Si quieres MOSTRAR el ID cuando editas, solo de lectura: 
    @if($carrera?->exists)
      <div class="form-group mb-2 mb20">
        <label class="form-label">ID</label>
        <input class="form-control" value="{{ $carrera->id_carrera }}" disabled>
      </div>
    @endif
    --}}

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar carrera') }}</button>              
    </div>
</div>
