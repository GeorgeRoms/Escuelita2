@extends('layouts.app')

@section('template_title')
    Crear Asignación de Clase
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Crear Asignación de Clase') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        
                        {{-- Muestra los errores de validación, incluyendo el error del TRIGGER --}}
                        @if ($errors->any())
                            <div class="alert alert-danger m-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('asignaciones.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            <div class="box box-info padding-1">
                                <div class="box-body">
                                    
                                    <h5 class="text-primary mb-3">Detalles de la Asignación</h5>

                                    {{-- Campo Profesor --}}
                                    <div class="form-group mb-3">
                                        <label for="profesor_id">{{ __('Profesor') }}</label>
                                        <select name="profesor_id" id="profesor_id" class="form-control @error('profesor_id') is-invalid @enderror">
                                            <option value="">-- Seleccione Profesor --</option>
                                            @foreach ($profesores as $profesor)
                                                {{-- Usamos id_profesor como clave para el value --}}
                                                <option value="{{ $profesor->id_profesor }}" 
                                                    {{ old('profesor_id') == $profesor->id_profesor ? 'selected' : '' }}>
                                                    {{ $profesor->nombre }} {{ $profesor->apellido_pat }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('profesor_id', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    
                                    {{-- Campo Materia --}}
                                    <div class="form-group mb-3">
                                        <label for="materia_id">{{ __('Materia') }}</label>
                                        <select name="materia_id" id="materia_id" class="form-control @error('materia_id') is-invalid @enderror">
                                            <option value="">-- Seleccione Materia --</option>
                                            @foreach ($materias as $materia)
                                                {{-- Usamos id_materia como clave para el value --}}
                                                <option value="{{ $materia->id_materia }}" 
                                                    {{ old('materia_id') == $materia->id_materia ? 'selected' : '' }}>
                                                    {{ $materia->nombre_mat }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('materia_id', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                    
                                    {{-- Campo Aula --}}
                                    <div class="form-group mb-3">
                                        <label for="aula_id">{{ __('Aula (Salón)') }}</label>
                                        <select name="aula_id" id="aula_id" class="form-control @error('aula_id') is-invalid @enderror">
                                            <option value="">-- Seleccione Aula --</option>
                                            @foreach ($aulas as $aula)
                                                {{-- Usamos id como clave para el value --}}
                                                <option value="{{ $aula->id }}" 
                                                    {{ old('aula_id') == $aula->id ? 'selected' : '' }}>
                                                    {{ $aula->salon }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('aula_id', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    <h5 class="text-primary mt-4 mb-3">Horario</h5>
                                    
                                    {{-- Campo Día de la Semana --}}
                                    <div class="form-group mb-3">
                                        <label for="dia_semana">{{ __('Día de la Semana') }}</label>
                                        <select name="dia_semana" id="dia_semana" class="form-control @error('dia_semana') is-invalid @enderror">
                                            <option value="">-- Seleccione Día --</option>
                                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'] as $dia)
                                                <option value="{{ $dia }}" 
                                                    {{ old('dia_semana') == $dia ? 'selected' : '' }}>
                                                    {{ $dia }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('dia_semana', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>

                                    {{-- Campo Hora de Inicio (y Duración, que es calculada) --}}
                                    <div class="form-group mb-3">
                                        <label for="hora_inicio">{{ __('Hora de Inicio (Duración: 2 horas)') }}</label>
                                        <select name="hora_inicio" id="hora_inicio" class="form-control @error('hora_inicio') is-invalid @enderror @error('horario') is-invalid @enderror">
                                            <option value="">-- Seleccione Hora (7:00 a 19:00) --</option>
                                            {{-- El controlador pasa $horariosDisponibles (ej: ['07:00:00' => '07:00 - 09:00']) --}}
                                            @foreach ($horariosDisponibles as $key => $label)
                                                <option value="{{ $key }}" 
                                                    {{ old('hora_inicio') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- Muestra error de validación estándar --}}
                                        {!! $errors->first('hora_inicio', '<div class="invalid-feedback">:message</div>') !!}
                                        {{-- Muestra el error lanzado por el TRIGGER (manejo en el controlador) --}}
                                        {!! $errors->first('horario', '<div class="invalid-feedback">:message</div>') !!}
                                        <small class="form-text text-muted">La hora de finalización se calcula automáticamente como dos horas después de la hora de inicio.</small>
                                    </div>

                                </div>
                                <div class="box-footer mt-4">
                                    <button type="submit" class="btn btn-primary">{{ __('Guardar Asignación') }}</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                
                {{-- Botón de Atrás --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary mt-3 mb-4">{{ __('Atrás') }}</a>
                </div>
            </div>
        </div>
    </section>
@endsection
