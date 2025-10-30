@extends('layouts.app')

@section('template_title')
    {{ __('Editar información del') }} curso
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="card-title">
                            {{ __('Editar información del') }} curso
                            <small class="text-muted ms-2">#{{ $curso->id_curso }}</small>
                        </span>
                    </div>

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cursos.update', $curso->id_curso) }}" role="form">
                            @method('PATCH')
                            @csrf

                            {{-- El partial ya incluye:
                                 - campos de Materia, Profesor, Aula, Periodo
                                 - nuevos campos: día_semana, hora_inicio, hora_fin
                                 - alerta de error 'horario' (trigger) --}}
                            @include('curso.form')

                        </form>
                    </div>

                    <div class="d-flex gap-2">
                        <x-back to="cursos.index" label="Cancelar" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
