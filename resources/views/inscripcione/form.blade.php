<div class="row padding-1 p-1">
    <div class="col-md-12">

        @php
        $alumnos = $alumnos ?? collect();
        $cursos  = $cursos  ?? collect();
        @endphp
        @php
        // Detecta si es edición (el create ya te pasa un modelo vacío new Inscripcione())
        $isEdit = isset($inscripcione) && ($inscripcione->id ?? false);
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
        {{-- <div class="form-group mb-2 mb20">
            <label class="form-label">Estado</label>
            @php $estSel = old('estado', $inscripcione->estado ?? 'Inscrito'); @endphp
            <select name="estado" class="form-select @error('estado') is-invalid @enderror">
            <option value="Inscrito" @selected($estSel==='Inscrito')>Inscrito</option>
            <option value="Baja"     @selected($estSel==='Baja')>Baja</option>
            </select>
            @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div> --}}
        {{-- <div class="form-group mb-2 mb20">
            <label for="oportunidad" class="form-label">{{ __('Oportunidad') }}</label>
            <input type="text" name="oportunidad" class="form-control @error('oportunidad') is-invalid @enderror" value="{{ old('oportunidad', $inscripcione?->oportunidad) }}" id="oportunidad" placeholder="Oportunidad">
            {!! $errors->first('oportunidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}
        <div class="form-group mb-2 mb20">
            <label class="form-label">Intento</label>
            @if($isEdit)
            {{-- En edición: solo mostrar el intento ya guardado, sin JS ni POST --}}
            <input type="text" class="form-control" value="{{ $inscripcione->intento ?? '—' }}" readonly>
            @else
            {{-- En creación: calcular dinámicamente --}}
            <input type="hidden" id="intento_url" value="{{ route('inscripciones.intento') }}">
            <input type="text" id="intento_preview" class="form-control" value="—" readonly>
            @error('intento')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            @endif
        </div>
        
        @if($isEdit)
    <div class="form-group mb-2 mb20">
        <label class="form-label">Promedio</label>
        <input type="number" step="0.01" min="0" max="100"
               name="promedio"
               class="form-control @error('promedio') is-invalid @enderror"
               value="{{ old('promedio', $inscripcione->promedio) }}">
        {!! $errors->first('promedio', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
    </div>
@endif

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

@if(!$isEdit)
<script>
(function(){
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', arguments.callee);
    return;
  }

  const alumnoSel = document.querySelector('[name="alumno_no_control"]');
  const cursoSel  = document.querySelector('[name="curso_id"]');
  const preview   = document.getElementById('intento_preview');
  const url       = document.getElementById('intento_url')?.value;
  const submitBtn = document.querySelector('button[type="submit"]');

  function actualizarBoton() {
    if (preview.value === 'APROBADA') {
      submitBtn?.setAttribute('disabled', 'disabled');
    } else {
      submitBtn?.removeAttribute('disabled');
    }
  }

  async function actualizarIntento(){
    const alumno = alumnoSel?.value?.trim();
    const curso  = cursoSel?.value?.trim();

    if (!alumno || !curso || !url) {
      preview.value = '—';
      actualizarBoton();
      return;
    }

    try{
      const qs  = new URLSearchParams({ alumno_no_control: alumno, curso_id: curso });
      const res = await fetch(`${url}?${qs.toString()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = res.ok ? await res.json() : null;
      preview.value = data?.intento ?? '—';
    }catch(_){
      preview.value = '—';
    }

    actualizarBoton();
  }

  alumnoSel?.addEventListener('change', actualizarIntento);
  cursoSel?.addEventListener('change', actualizarIntento);
  actualizarIntento(); // inicial
})();
</script>
@endif