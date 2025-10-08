@extends('layouts.app')

@section('template_title')
    {{ __('Actualización de carrera') }} Carrera
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Actualización de datos de ') }} carreras</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('carreras.update', $carrera->id_carrera) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('carrera.form')

                        </form>
                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="carreras.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5% ; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
