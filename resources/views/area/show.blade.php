@extends('layouts.app')

@section('template_title')
    {{ $area->name ?? __('Show') . " " . __('Area') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Area</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('areas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Area:</strong>
                                    {{ $area->id_area }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre Area:</strong>
                                    {{ $area->nombre_area }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Edificio:</strong>
                                    {{ $area->fk_edificio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Jefe:</strong>
                                    {{ $area->fk_jefe }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
