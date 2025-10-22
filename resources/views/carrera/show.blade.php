@extends('layouts.app')

@section('template_title')
    {{ $carrera->name ?? __('Información de') . " " . __('Carrera') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Información de') }} Carrera</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Código de Carrera:</strong>
                                    {{ $carrera->id_carrera }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre de la Carrera:</strong>
                                    {{ $carrera->nombre_carr }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Capacidad:</strong>
                                    {{ $carrera->capacidad }}
                                </div>

                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="carreras.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
