@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Contactos Alumno
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Contactos Alumno</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('contactos-alumnos.update', $contactosAlumno->id_contacto) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('contactos-alumno.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
