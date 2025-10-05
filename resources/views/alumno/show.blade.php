@extends('layouts.app')

@section('template_title')
    {{ $alumno->name ?? __('Show') . " " . __('Alumno') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Alumno</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('alumnos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>No Control:</strong>
                                    {{ $alumno->no_control }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $alumno->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Apellido Pat:</strong>
                                    {{ $alumno->apellido_pat }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Apellido Mat:</strong>
                                    {{ $alumno->apellido_mat }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Genero:</strong>
                                    {{ $alumno->genero }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Carrera:</strong>
                                    {{ $alumno->fk_carrera }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
