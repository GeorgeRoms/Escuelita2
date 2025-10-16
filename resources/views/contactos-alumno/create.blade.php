@extends('layouts.app')

@section('template_title')
    {{ __('Create') }} Contactos Alumno
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Registro de') }} contacto del alumno</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('contactos-alumnos.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('contactos-alumno.form')

                        </form>
                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="contactos-alumnos.index" label="Cancelar" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
