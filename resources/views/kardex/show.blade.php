@extends('layouts.app')

@section('template_title')
    {{ $kardex->name ?? __('Show') . " " . __('Kardex') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Kardex</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('kardexes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Kardex:</strong>
                                    {{ $kardex->id_kardex }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Alumno:</strong>
                                    {{ $kardex->fk_alumno }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Curso:</strong>
                                    {{ $kardex->fk_curso }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fecha Inscri:</strong>
                                    {{ $kardex->fecha_inscri }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $kardex->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Promedio:</strong>
                                    {{ $kardex->promedio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Oportunidad:</strong>
                                    {{ $kardex->oportunidad }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Intento:</strong>
                                    {{ $kardex->intento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Semestre:</strong>
                                    {{ $kardex->semestre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Unidades Reprobadas:</strong>
                                    {{ $kardex->unidades_reprobadas }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
