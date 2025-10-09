@php
  $catAlumnos = $alumnos ?? $catalAlumnos ?? collect();
  $catCursos  = $cursos  ?? $catalCursos  ?? collect();
@endphp
<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        {{-- <div class="form-group mb-2 mb20">
            <label for="id_kardex" class="form-label">{{ __('Id Kardex') }}</label>
            <input type="text" name="id_kardex" class="form-control @error('id_kardex') is-invalid @enderror" value="{{ old('id_kardex', $kardex?->id_kardex) }}" id="id_kardex" placeholder="Id Kardex">
            {!! $errors->first('id_kardex', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label for="fk_alumno" class="form-label">{{ __('Alumno') }}</label>
            @php $selA = old('fk_alumno',$kardex?->fk_alumno); @endphp
             <select name="fk_alumno" id="fk_alumno" class="form-select @error('fk_alumno') is-invalid @enderror">
                <option value="">— Selecciona —</option>
                @foreach ($catAlumnos as $key => $val)
                @php $id = is_string($key)?$key:$val; $label = is_string($val)?$val:$key; @endphp
            <option value="{{ $key }}" {{ (string)$selA===(string)$key ? 'selected':'' }}>{{ $label }}</option>
            @endforeach
            </select>
            {!! $errors->first('fk_alumno','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fk_curso" class="form-label">{{ __('Curso') }}</label>
            @php $selC = old('fk_curso',$kardex?->fk_curso); @endphp
            <select name="fk_curso" id="fk_curso" class="form-select @error('fk_curso') is-invalid @enderror">
                <option value="">— Selecciona —</option>
                @foreach ($catCursos as $id => $label)
            <option value="{{ $id }}" {{ (string)$selC===(string)$id ? 'selected':'' }}>{{ $label }}</option>
            @endforeach
            </select>
            {!! $errors->first('fk_curso','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_inscri" class="form-label">Fecha inscripción</label>
            <input type="date" name="fecha_inscri" id="fecha_inscri"
            class="form-control @error('fecha_inscri') is-invalid @enderror"
            value="{{ old('fecha_inscri',$kardex?->fecha_inscri) }}">
            {!! $errors->first('fecha_inscri','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">Estado</label>
            @php $est = old('estado',$kardex?->estado); @endphp
            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
            @foreach (['Activo','Baja','Baja temporal'] as $e)
            <option value="{{ $e }}" {{ $est===$e?'selected':'' }}>{{ $e }}</option>
            @endforeach
            </select>
            {!! $errors->first('estado','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="promedio" class="form-label">Promedio</label>
            <input type="number" step="0.01" name="promedio" id="promedio"
            class="form-control @error('promedio') is-invalid @enderror"
            value="{{ old('promedio',$kardex?->promedio) }}">
            {!! $errors->first('promedio','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="oportunidad" class="form-label">Oportunidad</label>
    @php $op = old('oportunidad',$kardex?->oportunidad); @endphp
    <select name="oportunidad" id="oportunidad" class="form-select @error('oportunidad') is-invalid @enderror">
      @foreach (['1ra','2da'] as $o)
        <option value="{{ $o }}" {{ $op===$o?'selected':'' }}>{{ $o }}</option>
      @endforeach
    </select>
    {!! $errors->first('oportunidad','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="intento" class="form-label">Intento</label>
    @php $in = old('intento',$kardex?->intento); @endphp
    <select name="intento" id="intento" class="form-select @error('intento') is-invalid @enderror">
      @foreach (['Normal','Repite','Especial'] as $i)
        <option value="{{ $i }}" {{ $in===$i?'selected':'' }}>{{ $i }}</option>
      @endforeach
    </select>
    {!! $errors->first('intento','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="semestre" class="form-label">Semestre</label>
    <input type="number" name="semestre" id="semestre"
      class="form-control @error('semestre') is-invalid @enderror"
      value="{{ old('semestre',$kardex?->semestre) }}">
    {!! $errors->first('semestre','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
             <label for="unidades_reprobadas" class="form-label">Unidades reprobadas</label>
    <input type="number" name="unidades_reprobadas" id="unidades_reprobadas"
      class="form-control @error('unidades_reprobadas') is-invalid @enderror"
      value="{{ old('unidades_reprobadas',$kardex?->unidades_reprobadas) }}">
    {!! $errors->first('unidades_reprobadas','<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>