@extends('layouts.app')

@section('template_title')
    {{ $alumno->nombre_completo }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Información del') }} Alumno</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>No de control:</strong>
                                    {{ $alumno->no_control }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $alumno->nombre_completo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Genero:</strong>
                                    {{ $alumno->genero_label ?? $alumno->genero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Carrera:</strong>
                                    {{ $alumno->carrera->nombre_carr ?? '—' }}
                                </div>
                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="alumnos.index" label="Atrás" style="margin-left: 1%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
