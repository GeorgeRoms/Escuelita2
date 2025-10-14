@extends('layouts.app')

@section('template_title')
    {{ $alumnoCarrera->name ?? __('Show') . " " . __('Alumno Carrera') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Alumno Carrera</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('alumno-carreras.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Alumno No Control:</strong>
                                    {{ $alumnoCarrera->alumno_no_control }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Carrera Id:</strong>
                                    {{ $alumnoCarrera->carrera_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estatus:</strong>
                                    {{ $alumnoCarrera->estatus }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Inicio:</strong>
                                    {{ $alumnoCarrera->fecha_inicio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Fin:</strong>
                                    {{ $alumnoCarrera->fecha_fin }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
