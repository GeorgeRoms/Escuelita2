@extends('layouts.app')

@section('template_title')
    {{ $contactosProfesore->name ?? __('Contacto del') . " " . __('profesor') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Contacto del') }} profesor</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Código de contacto:</strong>
                                    {{ $contactosProfesore->id_contacto }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Correo:</strong>
                                    {{ $contactosProfesore->correo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Telefono:</strong>
                                    {{ $contactosProfesore->telefono }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Dirección:</strong>
                                    {{ $contactosProfesore->direccion }}
                                </div>

                                <div class="form-group mb-2 mb20">
                                    <strong>Profesor:</strong>
                                    @php($p = $contacto->profesor)
                                    {{ $p ? trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? '')) : '—' }}
                                </div>

                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="contactos-profesores.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
