@extends('layouts.app')

@section('template_title')
    {{ $curso->name ?? __('Show') . " " . __('Curso') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Curso</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cursos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Curso:</strong>
                                    {{ $curso->id_curso }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Cupo:</strong>
                                    {{ $curso->cupo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Materia:</strong>
                                    {{ $curso->fk_materia }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Profesor:</strong>
                                    {{ $curso->fk_profesor }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Fk Edificio:</strong>
                                    {{ $curso->fk_edificio }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
