@extends('layouts.app')

@section('template_title')
    {{ $alumnoCarrera->name ?? ('Información de la carrera del alumno') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">Información de la carrera del alumno</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        @php
                        $al = $alumnoCarrera->alumno;
                        $ca = $alumnoCarrera->carrera;
                        @endphp
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Alumno:</strong>
                                    {{ $al ? ($al->no_control.' — '.$al->nombre.' '.$al->apellido_pat.' '.($al->apellido_mat ?? '')) : '—' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Carrera:</strong>
                                    {{ $ca->nombre_carr ?? '—' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estatus:</strong>
                                    {{ $alumnoCarrera->estatus }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Inicio:</strong>
                                    {{ $alumnoCarrera->fecha_inicio ?? '—' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Fin:</strong>
                                    {{ $alumnoCarrera->fecha_fin ?? '—' }}
                                </div>

                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="alumno-carreras.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5% ; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
