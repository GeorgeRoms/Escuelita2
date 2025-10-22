@extends('layouts.app')

@section('template_title') Detalle de inscripción @endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">Información de la inscripción</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        @php
                        $al = $inscripcione->alumno;
                        $cu = $inscripcione->curso;
                        $pr = $cu?->profesor;
                        $ma = $cu?->materia;
                        $au = $cu?->aula;
                        $ed = $au?->edificio;
                        $pe = $cu?->periodo;
                        @endphp
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Alumno:</strong>
                                    {{ $al ? ($al->no_control.' — '.$al->nombre.' '.$al->apellido_pat.' '.($al->apellido_mat ?? '')) : '—' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Curso:</strong>
                                    @if($cu)
                                    {{ $cu->id_curso }} — {{ $ma->nombre_mat ?? '—' }}
                                    @if($pr) ({{ $pr->nombre.' '.$pr->apellido_pat }}) @endif
                                    @if($ed && $au) [{{ $ed->codigo.' - '.$au->salon }}] @endif
                                    @if($pe) { {{ $pe->anio.' '.$pe->nombre }} } @endif
                                    @else — @endif
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $inscripcione->estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Intento:</strong>
                                    {{ $inscripcione->intento }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Promedio:</strong>
                                    {{ $inscripcione->promedio }}
                                </div>

                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="inscripciones.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
