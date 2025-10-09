@extends('layouts.app')

@section('template_title')
    {{ $contactosAlumno->name ?? __('Contacto del') . " " . __('alumno') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Contacto del') }} alumno</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Código de contacto:</strong>
                                    {{ $contactosAlumno->id_contacto }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Correo:</strong>
                                    {{ $contactosAlumno->correo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $contactosAlumno->telefono }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Dirección:</strong>
                                    {{ $contactosAlumno->direccion }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Alumno:</strong>
                                    @php($a = $contactosAlumno->alumno)
                                    {{ $a ? $a->no_control.' — '.trim($a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? '')) : '—' }}
                                </div>

                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="contactos-alumnos.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
