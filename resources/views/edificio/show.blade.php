@extends('layouts.app')

@section('template_title')
    {{ $edificio->name ?? __('Show') . " " . __('Edificio') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Edificio</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('edificios.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Edificio:</strong>
                                    {{ $edificio->edificio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Salon:</strong>
                                    {{ $edificio->salon }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
