@extends('layouts.app')

@section('template_title')
    {{ $materia->name ?? __('Show') . " " . __('Materia') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Materia</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('materias.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Materia:</strong>
                                    {{ $materia->id_materia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Mat:</strong>
                                    {{ $materia->nombre_mat }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Creditos:</strong>
                                    {{ $materia->creditos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Cadena:</strong>
                                    {{ $materia->fk_cadena }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
