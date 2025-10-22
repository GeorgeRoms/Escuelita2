@extends('layouts.app')

@section('template_title')
    {{ $curso->name ?? __('Información del') . " " . __('curso') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Información del') }} curso</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Código del curso:</strong>
                                    {{ $curso->id_curso }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cupo:</strong>
                                    {{ $curso->cupo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Materia:</strong>
                                    {{ $curso->materia->nombre_mat ?? '—' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Profesor:</strong>
                                    @php($p = $curso->profesor)
                                    {{ $p ? trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? '')) : '—' }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Aula:</strong>
                                    @php($a = $curso->aula)
                                    @php($e = $a?->edificio)
                                    {{ $a && $e ? ($e->codigo.' - '.$a->salon) : '—' }}
                                </div>
                                
                                <div class="form-group mb-2 mb20">
                                    <strong>Periodo:</strong>
                                    {{ $curso->periodo ? ($curso->periodo->anio.' '.$curso->periodo->nombre) : '—' }}
                                </div>
                        </div>
                        <div class="d-flex gap-2">
                        <x-back to="cursos.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
