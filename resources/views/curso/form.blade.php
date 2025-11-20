@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $msg)
                <li>{{ $msg }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="row padding-1 p-1">
  <div class="col-md-12">

    {{-- MENSAJE DE ERROR DEL TRIGGER (SOLAPAMIENTO) --}}
    @if($errors->has('horario'))
      <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-x-octagon-fill me-2"></i>
        <div><strong>Error de Horario:</strong> {{ $errors->first('horario') }}</div>
      </div>
    @endif

    <div class="form-group mb-2 mb20">
      <label for="cupo" class="form-label">{{ __('Cupo') }}</label>
      <input type="text" name="cupo" class="form-control @error('cupo') is-invalid @enderror"
             value="{{ old('cupo', $curso?->cupo) }}" id="cupo" placeholder="Cupo">
      {!! $errors->first('cupo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="form-group mb-3">
      <label for="fk_materia" class="form-label">Materia</label>
      <select name="fk_materia" id="fk_materia" class="form-control" required>
          <option value="" disabled selected>— Selecciona materia —</option>
          @foreach($materias as $id => $nombre)
              <option 
                  value="{{ $id }}"
                  data-creditos="{{ $materiasCred[$id] ?? '' }}"
                  @selected(old('fk_materia', $curso->fk_materia ?? null) == $id)>
                  {{ $nombre }}
              </option>
          @endforeach
      </select>
      <small class="form-text text-muted">
        Créditos: <strong id="lbl-creditos">—</strong>
      </small>
    </div>

    <div class="form-group mb-2 mb20">
      <label for="fk_profesor" class="form-label">{{ __('Profesor') }}</label>
      <select name="fk_profesor" id="fk_profesor"
              class="form-select @error('fk_profesor') is-invalid @enderror" required>
        <option value="">Selecciona…</option>
        @foreach($profesores as $id => $nombre)
          <option value="{{ $id }}" @selected(old('fk_profesor', $curso?->fk_profesor) == $id)>{{ $nombre }}</option>
        @endforeach
      </select>
      {!! $errors->first('fk_profesor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
    </div>

    <div class="form-group mb-2 mb20">
      <label for="aula_id" class="form-label">{{ __('Edificio - Aula') }}</label>
      <select name="aula_id" id="aula_id"
              class="form-select @error('aula_id') is-invalid @enderror">
        <option value="">— Sin aula asignada —</option>
        @foreach($aulas as $id => $label)
          <option value="{{ $id }}" @selected(old('aula_id', $curso->aula_id ?? null) == $id)>{{ $label }}</option>
        @endforeach
      </select>
      @error('aula_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mb-2 mb20">
      <label class="form-label">Periodo</label>
      <select name="periodo_id" class="form-select @error('periodo_id') is-invalid @enderror" required>
        <option value="">— Selecciona periodo —</option>
        @foreach($periodos as $id => $label)
          <option value="{{ $id }}" @selected(old('periodo_id', $curso->periodo_id ?? null) == $id)>{{ $label }}</option>
        @endforeach
      </select>
      @error('periodo_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- ======= NUEVOS CAMPOS: HORARIO ======= --}}
    @php
    $diasListado = [
        'Lunes-Miércoles-Viernes',
        'Martes-Jueves-Viernes',
        'Lunes-Miércoles',
        'Martes-Jueves'
    ];
@endphp

    <div class="form-group mb-3">
      <label for="dia_semana" class="form-label">Días de la semana</label>
        <select name="dia_semana" id="dia_semana" class="form-control" 
              required
              data-current="{{ old('dia_semana', $curso->dia_semana ?? '') }}">
        <option value="" disabled selected>— Selecciona días —</option>
        </select>
      <small id="ayuda-dia" class="form-text text-muted d-none"></small>
    </div>

    {{-- <div class="form-group mb-2 mb20">
      <label for="hora_inicio" class="form-label">Hora de inicio</label>
      <input type="time" name="hora_inicio" id="hora_inicio"
             value="{{ old('hora_inicio', optional($curso->hora_inicio ?? null)->format('H:i')) }}"
             class="form-control @error('hora_inicio') is-invalid @enderror" required>
      @error('hora_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="form-group mb-3 mb20">
      <label for="hora_fin" class="form-label">Hora de fin</label>
      <input type="time" name="hora_fin" id="hora_fin"
             value="{{ old('hora_fin', optional($curso->hora_fin ?? null)->format('H:i')) }}"
             class="form-control @error('hora_fin') is-invalid @enderror" required>
      @error('hora_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div> --}}
    
    @php
    // Para modo edición, formateamos las horas a HH:MM
    $horaInicio = old('hora_inicio');
    $horaFin    = old('hora_fin');

    if (!$horaInicio && isset($curso) && $curso->hora_inicio) {
        // con el cast de Eloquent a datetime:H:i ya es Carbon
        $horaInicio = optional($curso->hora_inicio)->format('H:i');
    }

    if (!$horaFin && isset($curso) && $curso->hora_fin) {
        $horaFin = optional($curso->hora_fin)->format('H:i');
    }
@endphp

<div class="row g-3">
  <div class="col-md-6">
    <label for="hora_inicio" class="form-label">Hora inicio (bloque 2 h)</label>
    <input
        type="time"
        name="hora_inicio"
        id="hora_inicio"
        class="form-control @error('hora_inicio') is-invalid @enderror"
        value="{{ $horaInicio }}"
        required
        min="07:00"
        max="19:00"  {{-- 19:00 + 2h = 21:00 --}}
    >
    @error('hora_inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
  <div class="col-md-6">
    <label for="hora_fin" class="form-label">Hora fin (bloque 2 h)</label>
    <input
        type="time"
        name="hora_fin"
        id="hora_fin"
        class="form-control @error('hora_fin') is-invalid @enderror"
        value="{{ $horaFin }}"
        readonly  {{-- ⚠️ antes estaba "disabled" --}}
    >
    @error('hora_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>

    <small class="text-muted d-block mt-1">Sugerencia: fija aquí el bloque de <b>2 horas</b> (p. ej. 07:00–09:00).</small>

@php
    $horaInicio1h = old('hora_inicio_1h');
    $horaFin1h    = old('hora_fin_1h');

    if (!$horaInicio1h && isset($curso) && $curso->hora_inicio_1h) {
        $horaInicio1h = \Illuminate\Support\Carbon::parse($curso->hora_inicio_1h)->format('H:i');
    }
    if (!$horaFin1h && isset($curso) && $curso->hora_fin_1h) {
        $horaFin1h = \Illuminate\Support\Carbon::parse($curso->hora_fin_1h)->format('H:i');
    }
@endphp


    <input type="hidden" name="dia_1h" id="dia_1h" value="{{ old('dia_1h', $curso->dia_1h ?? '') }}">

    <div id="bloque-1h" class="border rounded p-3 mt-3 d-none">
      <h6 class="mb-2">
        Día de 1 hora: <span id="lbl-dia-1h" class="badge bg-info"></span>
      </h6>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="hora_inicio_1h" class="form-label">Hora inicio (1 h)</label>
          <input
    type="time"
    name="hora_inicio_1h"
    id="hora_inicio_1h"
    class="form-control @error('hora_inicio_1h') is-invalid @enderror"
    value="{{ $horaInicio1h }}"
    min="07:00"
    max="20:00" {{-- 20:00 + 1h = 21:00 --}}
>
@error('hora_inicio_1h') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
          <label for="hora_fin_1h" class="form-label">Hora fin (1 h)</label>
          <input
    type="time"
    name="hora_fin_1h"
    id="hora_fin_1h"
    class="form-control @error('hora_fin_1h') is-invalid @enderror"
    value="{{ $horaFin1h }}"
    readonly  {{-- igual que arriba: NO disabled --}}
>
@error('hora_fin_1h') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
      </div>
      <small class="text-muted d-block mt-1">Este bloque solo aplica cuando el patrón incluye <b>un día de 1 h</b> (créditos 5 o 3).</small>
    </div>

  </div>

  <div class="col-md-12 mt20 mt-2">
    <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
  </div>
</div>

<script>
(function () {
  const selMateria   = document.getElementById('fk_materia');
  const lblCreditos  = document.getElementById('lbl-creditos');
  const selDias      = document.getElementById('dia_semana');
  const ayudaDia     = document.getElementById('ayuda-dia');

  const hora2Ini     = document.getElementById('hora_inicio');
  const hora2Fin     = document.getElementById('hora_fin');

  const bloque1h     = document.getElementById('bloque-1h');
  const lblDia1h     = document.getElementById('lbl-dia-1h');
  const inpDia1h     = document.getElementById('dia_1h');
  const hora1Ini     = document.getElementById('hora_inicio_1h');
  const hora1Fin     = document.getElementById('hora_fin_1h');

  const COMBINACIONES = {
    5: [
      { value: 'Lunes-Miércoles-Viernes', label: 'Lunes, Miércoles y Viernes', dia1h: 'Viernes' },
      { value: 'Martes-Jueves-Viernes',   label: 'Martes, Jueves y Viernes',   dia1h: 'Viernes' }
    ],
    4: [
      { value: 'Lunes-Miércoles', label: 'Lunes y Miércoles', dia1h: null },
      { value: 'Martes-Jueves',   label: 'Martes y Jueves',   dia1h: null }
    ],
    3: [
      { value: 'Lunes-Miércoles', label: 'Lunes y Miércoles (3 h)', dia1h: 'Miércoles' },
      { value: 'Martes-Jueves',   label: 'Martes y Jueves (3 h)',   dia1h: 'Jueves' }
    ]
  };

  function poblarDias(creditos) {
    selDias.innerHTML = '<option value="" disabled selected>— Selecciona días —</option>';
    const combos = COMBINACIONES[creditos] || [];
    combos.forEach(c => {
      const opt = document.createElement('option');
      opt.value = c.value;
      opt.textContent = c.label;
      opt.dataset.dia1h = c.dia1h || '';
      selDias.appendChild(opt);
    });
    ayudaDia.classList.toggle('d-none', combos.length === 0);
    ayudaDia.innerHTML =
      (creditos === 5) ? '5 créditos → 2h + 2h + 1h. El día de 1 h es <b>Viernes</b>.'
      : (creditos === 4) ? '4 créditos → 2h + 2h (sin día de 1 h).'
      : (creditos === 3) ? '3 créditos → 2h + 1h. El día de 1 h depende del par seleccionado.'
      : 'Selecciona una materia para ver opciones.';
  }

  function refrescarBloque1h() {
    const opt = selDias.options[selDias.selectedIndex];
    if (!opt) return;

    const dia1h = opt.dataset.dia1h || '';
    if (dia1h) {
      lblDia1h.textContent = dia1h;
      inpDia1h.value = dia1h;              // << guarda el día de 1 h
      bloque1h.classList.remove('d-none'); // mostrar bloque
    } else {
      bloque1h.classList.add('d-none');    // ocultar
      lblDia1h.textContent = '';
      inpDia1h.value = '';                 // limpiar
      hora1Ini.value = '';
      hora1Fin.value = '';
    }
  }

  function diffMin(h1, h2) {
    const [hA, mA] = h1.split(':').map(Number);
    const [hB, mB] = h2.split(':').map(Number);
    return (hB * 60 + mB) - (hA * 60 + mA);
  }

  function validarHoras() {
    // 2 horas
    if (hora2Ini.value && hora2Fin.value) {
      const ok2h = diffMin(hora2Ini.value, hora2Fin.value) === 120;
      hora2Fin.setCustomValidity(ok2h ? '' : 'El bloque principal debe ser de 2 horas');
    } else {
      hora2Fin.setCustomValidity('');
    }
    // 1 hora (solo si visible)
    if (!bloque1h.classList.contains('d-none') && hora1Ini.value && hora1Fin.value) {
      const ok1h = diffMin(hora1Ini.value, hora1Fin.value) === 60;
      hora1Fin.setCustomValidity(ok1h ? '' : 'El bloque adicional debe ser de 1 hora');
    } else {
      hora1Fin.setCustomValidity('');
    }
  }

  selMateria?.addEventListener('change', function () {
    const creditos = parseInt(selMateria.selectedOptions[0]?.dataset?.creditos || 'NaN', 10);
    lblCreditos.textContent = isNaN(creditos) ? '—' : creditos;
    poblarDias(creditos);
    selDias.value = '';
    refrescarBloque1h();
  });

  selDias?.addEventListener('change', function () {
    refrescarBloque1h();
  });

  [hora2Ini, hora2Fin, hora1Ini, hora1Fin].forEach(el => el?.addEventListener('input', validarHoras));

  // Init (modo edición)
  (function initFromOldValues(){
    const optSel = selMateria?.selectedOptions?.[0];
    if (optSel?.dataset?.creditos) {
      lblCreditos.textContent = optSel.dataset.creditos;
      poblarDias(parseInt(optSel.dataset.creditos, 10));
    }
    if (selDias && selDias.dataset.current) {
      selDias.value = selDias.dataset.current;
    }
    // Restaura día 1h si viene de old/edición
    if (inpDia1h.value) {
      const opt = [...selDias.options].find(o => (o.dataset.dia1h || '') === inpDia1h.value);
      if (opt && !selDias.value) selDias.value = opt.value;
    }
    refrescarBloque1h();
    validarHoras();
  })();

  // === util: sumar minutos a una 'HH:MM' ===
function addMinutes(hhmm, minutes) {
  if (!hhmm) return '';
  const [h, m] = hhmm.split(':').map(Number);
  let total = h * 60 + m + minutes;
  // normaliza a 0..1439 por si se cruza de día
  total = ((total % 1440) + 1440) % 1440;
  const H = String(Math.floor(total / 60)).padStart(2, '0');
  const M = String(total % 60).padStart(2, '0');
  return `${H}:${M}`;
}

// === auto-sync fin (2 h) cuando cambia inicio (2 h) ===
function syncFin2h() {
  if (!hora2Ini) return;
  if (hora2Ini.value) {
    hora2Fin.value = addMinutes(hora2Ini.value, 120);
  } else {
    hora2Fin.value = '';
  }
  validarHoras();
}

// === auto-sync fin (1 h) cuando cambia inicio (1 h) ===
function syncFin1h() {
  if (!hora1Ini || !hora1Fin) return;
  if (hora1Ini.value) {
    hora1Fin.value = addMinutes(hora1Ini.value, 60);
  } else {
    hora1Fin.value = '';
  }
  validarHoras();
}

// listeners nuevos
hora2Ini?.addEventListener('change', syncFin2h);
hora1Ini?.addEventListener('change', syncFin1h);

// cuando se muestre/oculte el bloque de 1 h, autollenar si aplica
const _refrescarBloque1h_original = refrescarBloque1h;
refrescarBloque1h = function () {
  _refrescarBloque1h_original();   // corre tu lógica actual
  // si el bloque 1 h está visible y hay inicio, autocalcula fin
  if (!bloque1h.classList.contains('d-none') && hora1Ini?.value && !hora1Fin?.value) {
    syncFin1h();
  }
};

// también al iniciar (modo edición) sincroniza por si ya viene inicio
document.addEventListener('DOMContentLoaded', () => {
  if (hora2Ini?.value && !hora2Fin?.value) syncFin2h();
  if (!bloque1h.classList.contains('d-none') && hora1Ini?.value && !hora1Fin?.value) syncFin1h();
});


})();
</script>
