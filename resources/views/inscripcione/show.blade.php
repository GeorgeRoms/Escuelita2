@extends('layouts.app')

@section('template_title')
    {{ $inscripcione->name ?? __('Show') . " " . __('Inscripcione') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Inscripcione</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('inscripciones.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Alumno No Control:</strong>
                                    {{ $inscripcione->alumno_no_control }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Curso Id:</strong>
                                    {{ $inscripcione->curso_id }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $inscripcione->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Oportunidad:</strong>
                                    {{ $inscripcione->oportunidad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Intento:</strong>
                                    {{ $inscripcione->intento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Semestre:</strong>
                                    {{ $inscripcione->semestre }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
