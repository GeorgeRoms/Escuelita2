{{-- resources/views/reportes/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    // Se inicializan variables para evitar errores si no se pasan desde el controlador
    $selCarrera = $carrera ?? request('carrera');
    $selIntento = $intento ?? request('intento', 'Especial');
@endphp

{{-- Bloque de mensajes de sesión (para mostrar "NO EXISTE" o éxito) --}}
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error:</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Éxito:</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="container">
    <h3 class="mb-3">Reportes</h3>

    <div class="row g-3">
        {{-- Reporte: Alumnos/Materias en “Especial” --}}
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Alumnos con materias en “Normal/Repite/Especial”</h5>
                    <p class="text-muted mb-3">Resumen por carrera usando el procedimiento almacenado.</p>

                    <form action="{{ route('reportes.especial') }}" method="get" class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Carrera</label>
                            <select name="carrera" class="form-select" required>
                                <option value="" disabled {{ $selCarrera ? '' : 'selected' }}>— elige una carrera —</option>
                                @foreach(($carreras ?? []) as $car)
                                    <option value="{{ $car }}" {{ $selCarrera === $car ? 'selected' : '' }}>
                                        {{ $car }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label">Intento</label>
                            <select name="intento" class="form-select" required>
                                <option value="" disabled {{ $selIntento ? '' : 'selected' }}>— Intento —</option>
                                @foreach(['Normal','Repite','Especial'] as $opt)
                                    <option value="{{ $opt }}" {{ $selIntento === $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <button class="btn btn-primary w-100" type="submit">Ver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Reporte: Alumnos por curso (usa SP alumnos_por_curso) --}}
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Alumnos por Curso</h5>
                    <p class="text-muted mb-3">Lista alumnos (no_control, alumno, semestre, intento, profesor, materia, periodo).</p>

                    <form action="{{ route('reportes.curso.ver') }}" method="get" class="d-flex gap-2">
                        <select name="curso_id" class="form-select" required>
                            <option value="" disabled selected>— elige un curso —</option>
                            @foreach($cursos ?? [] as $c)
                                <option value="{{ $c->id_curso }}">{{ $c->etiqueta }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">Ver</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Reporte: Materias impartidas por profesor (CON FILTRO DINÁMICO) --}}
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Materias impartidas por profesor</h5>
                    <p class="text-muted mb-3">Lista cursos del docente y cuántos alumnos inscritos tiene cada uno.</p>

                    <form action="{{ route('reportes.profesor.ver') }}" method="get" class="row g-2">
                        
                        {{-- CAMPO: FILTRO POR ÁREA --}}
                        <div class="col-12">
                            <label class="form-label">Área</label>
                            <select name="area_id" id="area_select_filtro" class="form-select" required>
                                <option value="" disabled selected>— elige un área —</option>
                                @foreach($areas ?? [] as $area)
                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- CAMPO PROFESOR (A cargarse por AJAX) --}}
                        <div class="col-12">
                            <label class="form-label">Profesor</label>
                            <select name="profesor_id" id="profesor_select_filtrado" class="form-select" required disabled>
                                <option value="" disabled selected>— Selecciona un área primero —</option>
                            </select>
                        </div>
                        
                        {{-- CAMPO PERIODO --}}
                        <div class="col-12">
                            <label class="form-label">Periodo (opcional)</label>
                            <select name="periodo_id" class="form-select">
                                <option value="">— todos —</option>
                                @foreach($periodos ?? [] as $per)
                                    <option value="{{ $per->id }}">{{ $per->etiqueta }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <button class="btn btn-primary w-100" type="submit">Ver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Reporte: Historial de un alumno --}}
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Historial de un alumno</h5>
                    <p class="text-muted mb-3">Consulta qué materias ha tomado, con qué profesor y en qué periodo.</p>

                    <form action="{{ route('reportes.alumno.ver') }}" method="get" class="d-flex gap-2">
            
                        <input
                            type="text"
                            class="form-control"
                            id="numero_control_autocomplete" 
                            name="no_control"
                            placeholder="Escribe el Número de Control (Ej: 202510001)"
                            required
                            maxlength="9" {{-- REQUERIDO: Limita a 9 caracteres (el número de control) --}}
                            pattern="[0-9]{9}" {{-- OPCIONAL: Asegura que sean 9 dígitos exactos --}}
                        >
            
                        <button class="btn btn-primary" type="submit">Ver</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Resumen por Carrera + Periodo</h5>
                    <p class="text-muted mb-3">KPIs, materias, profesores y alumnos del periodo seleccionado.</p>

                    <form action="{{ route('reportes.carrera_periodo.ver') }}" method="get" class="row g-2">
                        <div class="col-12">
                            <label class="form-label">Carrera</label>
                            <select name="carrera" class="form-select" required>
                                <option value="" disabled selected>— elige una carrera —</option>
                                @foreach($carreras ?? [] as $car)
                                    <option value="{{ $car }}">{{ $car }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Periodo</label>
                            <select name="periodo_id" class="form-select" required>
                                <option value="" disabled selected>— elige un periodo —</option>
                                @foreach($periodos ?? [] as $per)
                                    <option value="{{ $per->id }}">{{ $per->etiqueta }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100" type="submit">Ver</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Bloque JavaScript para el filtro dinámico Área -> Profesor --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const areaSelect = document.getElementById('area_select_filtro');
        const profesorSelect = document.getElementById('profesor_select_filtrado');

        if (areaSelect && profesorSelect) {
            areaSelect.addEventListener('change', function() {
                const areaId = this.value;
                profesorSelect.innerHTML = '<option value="" disabled selected>Cargando profesores...</option>';
                profesorSelect.disabled = true;

                if (areaId) {
                    // **Ruta AJAX**: Debe coincidir con la ruta definida en routes/web.php
                    const url = `/reportes/profesores/por_area/${areaId}`; 

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                // Lanza un error si el servidor no responde con 200 OK
                                throw new Error('Error en la red o servidor. Estado: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(profesores => {
                            // Limpiar y añadir la opción por defecto
                            profesorSelect.innerHTML = '<option value="" disabled selected>— elige un profesor —</option>';
                            
                            if (profesores.length > 0) {
                                profesores.forEach(profesor => {
                                    // Asume que el objeto profesor tiene id, nombre, apellido_paterno y apellido_materno
                                    const nombreCompleto = 
                                        `${profesor.nombre} ${profesor.apellido_paterno} ${profesor.apellido_materno || ''}`.trim();

                                    const option = document.createElement('option');
                                    option.value = profesor.id;
                                    option.textContent = nombreCompleto;
                                    profesorSelect.appendChild(option);
                                });
                                profesorSelect.disabled = false;
                            } else {
                                profesorSelect.innerHTML = '<option value="" disabled selected>— No hay profesores en esta área —</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error al cargar profesores:', error);
                            profesorSelect.innerHTML = '<option value="" disabled selected>Error al cargar profesores</option>';
                        });
                } else {
                    profesorSelect.innerHTML = '<option value="" disabled selected>— Selecciona un área primero —</option>';
                }
            });
        }
    });
</script>
@endsection
