@extends('layouts.app')

@section('template_title')
    {{ $profesore->name ?? __('Datos del') . " " . __('profesor') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Datos del') }} profesor</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('profesores.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Código de profesor:</strong>
                                    {{ $profesore->id_profesor }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $profesore->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Apellido Pat:</strong>
                                    {{ $profesore->apellido_pat }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Apellido Mat:</strong>
                                    {{ $profesore->apellido_mat }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Area:</strong>
                                    {{ $profesore->area->nombre_area ?? '—' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo:</strong>
                                    {{ $profesore->tipo }}
                                </div>

                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="profesores.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
