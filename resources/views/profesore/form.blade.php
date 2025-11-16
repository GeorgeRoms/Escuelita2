<div class="box box-info padding-1">
    <div class="box-body">
        
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre"
                   class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $profesore?->nombre) }}"
                   id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="apellido_pat" class="form-label">{{ __('Apellido Paterno') }}</label>
            <input type="text" name="apellido_pat"
                   class="form-control @error('apellido_pat') is-invalid @enderror"
                   value="{{ old('apellido_pat', $profesore?->apellido_pat) }}"
                   id="apellido_pat" placeholder="Paterno">
            {!! $errors->first('apellido_pat', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>
        
        <div class="form-group mb-2 mb20">
            <label for="apellido_mat" class="form-label">{{ __('Apellido Materno') }}</label>
            <input type="text" name="apellido_mat"
                   class="form-control @error('apellido_mat') is-invalid @enderror"
                   value="{{ old('apellido_mat', $profesore?->apellido_mat) }}"
                   id="apellido_mat" placeholder="Materno">
            {!! $errors->first('apellido_mat', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        {{-- ÁREA --}}
        <div class="form-group mb-2 mb20">
            <label for="fk_area" class="form-label">{{ __('Área') }}</label>
            <select name="fk_area" id="fk_area"
                    class="form-select @error('fk_area') is-invalid @enderror" required>
                <option value="">Seleccione...</option>
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

        {{-- TIPO --}}
        <div class="form-group mb-2 mb20">
            <label for="tipo" class="form-label">{{ __('Tipo') }}</label>
            @php($opciones = ['Tiempo completo', 'Medio Tiempo', 'Asignatura'])
            @php($sel = old('tipo', $profesore?->tipo))
            
            <select name="tipo" id="tipo"
                    class="form-select @error('tipo') is-invalid @enderror">
                <option value="">Seleccione...</option>
                @foreach ($opciones as $n)
                    <option value="{{ $n }}" @selected((string)$sel === (string)$n)>{{ $n }}</option>
                @endforeach
            </select>
            {{-- aquí el error debe ser de "tipo", no de "creditos" --}}
            {!! $errors->first('tipo', '<div class="invalid-feedback"><strong>:message</strong></div>') !!} 
        </div>

        <hr>

{{-- Encabezado con botón para enroscar / desenroscar --}}
<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Datos de contacto</h5>

    <button class="btn btn-link text-decoration-none p-0 d-flex align-items-center"
            type="button"
            data-bs-toggle="collapse"          {{-- si usas Bootstrap 4 cambia a: data-toggle="collapse" --}}
            data-bs-target="#contactoCollapse"
            aria-expanded="false"
            aria-controls="contactoCollapse">
        <span class="me-1" id="contactoCollapseText">Mostrar</span>
        <i class="bi bi-chevron-down" id="contactoCollapseIcon"></i>
    </button>
</div>

{{-- Contenedor colapsable --}}
<div class="collapse" id="contactoCollapse">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo"
                   class="form-control @error('correo') is-invalid @enderror"
                   value="{{ old('correo', $profesore->contacto?->correo ?? '') }}">
            {!! $errors->first('correo', '<div class="invalid-feedback d-block"><strong>:message</strong></div>') !!}
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="{{ old('telefono', $profesore->contacto?->telefono ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Calle</label>
            <input type="text" name="calle" class="form-control"
                   value="{{ old('calle', $profesore->contacto?->calle ?? '') }}">
        </div>

        <div class="col-md-6 mb-3">
            <label class="form-label">Colonia</label>
            <input type="text" name="colonia" class="form-control"
                   value="{{ old('colonia', $profesore->contacto?->colonia ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Número exterior</label>
            <input type="text" name="num_ext" class="form-control"
                   value="{{ old('num_ext', $profesore->contacto?->num_ext ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Número interior (opcional)</label>
            <input type="text" name="num_int" class="form-control"
                   value="{{ old('num_int', $profesore->contacto?->num_int ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Código postal</label>
            <input type="text" name="cp" class="form-control"
                   value="{{ old('cp', $profesore->contacto?->cp ?? '') }}">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Estado</label>
            <input type="text" name="estado" class="form-control"
                   value="{{ old('estado', $profesore->contacto?->estado ?? '') }}">
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">País</label>
            <input type="text" name="pais" class="form-control"
                   value="{{ old('pais', $profesore->contacto?->pais ?? '') }}">
        </div>
    </div>
</div>



    <div class="box-footer mt20">
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    var col = document.getElementById('contactoCollapse');
    if (!col) return;

    col.addEventListener('shown.bs.collapse', function () {
        document.getElementById('contactoCollapseText').textContent = 'Ocultar';
        document.getElementById('contactoCollapseIcon').classList.remove('bi-chevron-down');
        document.getElementById('contactoCollapseIcon').classList.add('bi-chevron-up');
    });

    col.addEventListener('hidden.bs.collapse', function () {
        document.getElementById('contactoCollapseText').textContent = 'Mostrar';
        document.getElementById('contactoCollapseIcon').classList.remove('bi-chevron-up');
        document.getElementById('contactoCollapseIcon').classList.add('bi-chevron-down');
    });
});
</script>
