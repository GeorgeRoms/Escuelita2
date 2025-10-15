@extends('layouts.app')

@section('template_title')
    {{ __('Inscripción de alumno') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Inscripción de alumno') }}</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('inscripciones.store') }}">
                            @csrf
                            @include('inscripcione.form', ['inscripcione' => new \App\Models\Inscripcione()])
                        </form>
                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="inscripciones.index" label="Cancelar" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
