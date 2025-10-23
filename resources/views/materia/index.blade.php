@extends('layouts.app')

@section('template_title')
    {{ __('Editar') }} Asignación
@endsection

@section('content')
    <section class="content container-fluid mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <span class="card-title">{{ __('Editar') }} Asignación de Clase</span>
                    </div>



                    <div class="card-body bg-white">
                        
                        <!-- Usamos el método PATCH para la actualización -->
                        <form method="POST" action="{{ route('asignaciones.update', $asignacion->id) }}"  role="form">
                            @csrf
                            @method('PATCH')

                            <!-- MENSAJE DE ERROR DEL TRIGGER (SOLAPAMIENTO) -->
                            @if($errors->has('horario'))
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-x-octagon-fill me-2"></i> 
                                    <strong>Error de Horario:</strong> {{ $errors->first('horario') }}
                                </div>
                            @endif

                            <!-- PROFESOR -->
                            <div class="mb-3">
                                <label for="profesor_id" class="form-label">Profesor</label>
                                <select name="profesor_id" id="profesor_id" class="form-select @error('profesor_id') is-invalid @enderror" required>
                                    <option value="">Seleccione Profesor</option>
                                    @foreach ($profesores as $profesor)
                                        <option value="{{ $profesor->id_profesor }}" 
                                            {{ old('profesor_id', $asignacion->profesor_id) == $profesor->id_profesor ? 'selected' : '' }}>
                                            {{ $profesor->nombre ?? 'Nombre Desconocido' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('profesor_id')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <!-- MATERIA -->
                            <div class="mb-3">
                                <label for="materia_id" class="form-label">Materia</label>
                                <select name="materia_id" id="materia_id" class="form-select @error('materia_id') is-invalid @enderror" required>
                                    <option value="">Seleccione Materia</option>
                                    @foreach ($materias as $materia)
                                        <option value="{{ $materia->id_materia }}" 
                                            {{ old('materia_id', $asignacion->materia_id) == $materia->id_materia ? 'selected' : '' }}>
                                            {{ $materia->nombre_mat ?? 'Materia Desconocida' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('materia_id')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <!-- AULA -->
                            <div class="mb-3">
                                <label for="aula_id" class="form-label">Aula / Salón</label>
                                <select name="aula_id" id="aula_id" class="form-select @error('aula_id') is-invalid @enderror" required>
                                    <option value="">Seleccione Aula</option>
                                    @foreach ($aulas as $aula)
                                        <option value="{{ $aula->id }}" 
                                            {{ old('aula_id', $asignacion->aula_id) == $aula->id ? 'selected' : '' }}>
                                            {{ $aula->nombre ?? 'Aula Desconocida' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('aula_id')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <!-- DÍA DE LA SEMANA -->
                            <div class="mb-3">
                                <label for="dia_semana" class="form-label">Día de la Semana</label>
                                <select name="dia_semana" id="dia_semana" class="form-select @error('dia_semana') is-invalid @enderror" required>
                                    <option value="">Seleccione el día</option>
                                    @php
                                        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                                    @endphp
                                    @foreach ($dias as $dia)
                                        <option value="{{ $dia }}" 
                                            {{ old('dia_semana', $asignacion->dia_semana) == $dia ? 'selected' : '' }}>{{ $dia }}</option>
                                    @endforeach
                                </select>
                                @error('dia_semana')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <!-- HORARIO (GENERADO) -->
                            <div class="mb-3">
                                <label for="hora_inicio" class="form-label">Horario (Inicio - Fin: 2 horas)</label>
                                <select name="hora_inicio" id="hora_inicio" class="form-select @error('hora_inicio') is-invalid @enderror" required>
                                    <option value="">Seleccione el horario de inicio</option>
                                    
                                    @foreach ($horariosDisponibles as $key => $label)
                                        <option value="{{ $key }}" 
                                            {{ old('hora_inicio', $asignacion->hora_inicio) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('hora_inicio')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                                <small class="form-text text-muted">La hora de fin se calcula automáticamente (+2 horas).</small>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-success me-md-2">Guardar Cambios</button>
                                <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                </div>



            </div>
        </div>
    </section>
@endsection




