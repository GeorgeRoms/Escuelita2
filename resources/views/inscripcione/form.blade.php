<div class="row padding-1 p-1">
    <div class="col-md-12">

        @php
        $alumnos = $alumnos ?? collect();
        $cursos  = $cursos  ?? collect();
        @endphp
        
        <div class="form-group mb-2 mb20">
            <label class="form-label">Alumno</label>
            <select name="alumno_no_control" class="form-select @error('alumno_no_control') is-invalid @enderror">
            <option value="">Seleccione…</option>
            @foreach($alumnos as $no => $label)
            <option value="{{ $no }}" @selected(old('alumno_no_control', $inscripcione->alumno_no_control ?? null) == $no)>
            {{ $label }}
            </option>
            @endforeach
            </select>
            @error('alumno_no_control') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group mb-2 mb20">
            <label class="form-label">Curso</label>
            <select name="curso_id" class="form-select @error('curso_id') is-invalid @enderror">
            <option value="">Seleccione…</option>
            @foreach($cursos as $id => $label)
            <option value="{{ $id }}" @selected(old('curso_id', $inscripcione->curso_id ?? null) == $id)>
            {{ $label }}
            </option>
            @endforeach
            </select>
            @error('curso_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="form-group mb-2 mb20">
            <label class="form-label">Estado</label>
            @php $estSel = old('estado', $inscripcione->estado ?? 'Inscrito'); @endphp
            <select name="estado" class="form-select @error('estado') is-invalid @enderror">
            <option value="Inscrito" @selected($estSel==='Inscrito')>Inscrito</option>
            <option value="Baja"     @selected($estSel==='Baja')>Baja</option>
            </select>
            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        {{-- <div class="form-group mb-2 mb20">
            <label for="oportunidad" class="form-label">{{ __('Oportunidad') }}</label>
            <input type="text" name="oportunidad" class="form-control @error('oportunidad') is-invalid @enderror" value="{{ old('oportunidad', $inscripcione?->oportunidad) }}" id="oportunidad" placeholder="Oportunidad">
            {!! $errors->first('oportunidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label class="form-label">Intento</label>
            @php $intSel = old('intento', $inscripcione->intento ?? 'Normal'); @endphp
            <select name="intento" class="form-select @error('intento') is-invalid @enderror">
            <option value="Normal"  @selected($intSel==='Normal')>Normal</option>
            <option value="Repite"  @selected($intSel==='Repite')>Repite</option>
            <option value="Especial"@selected($intSel==='Especial')>Especial</option>
            </select>
            @error('intento') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label for="promedio" class="form-label">Promedio</label>
            <input
                type="number"
                name="promedio"
                id="promedio"
                class="form-control @error('promedio') is-invalid @enderror"
                value="{{ old('promedio', $inscripcione->promedio ?? null) }}"
                step="0.01" min="0" max="100" placeholder="0.00 – 100.00">
                {!! $errors->first('promedio', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        {{-- <div class="form-group mb-2 mb20">
            <label class="form-label">Semestre</label>
            <input type="number" name="semestre" min="1" max="12"
               class="form-control @error('semestre') is-invalid @enderror"
               value="{{ old('semestre', $inscripcione->semestre ?? null) }}">
                @error('semestre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div> --}}

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>