@extends('layouts.app')

@section('template_title')
    {{ __('Registrar') }} curso
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="card-title">{{ __('Registrar') }} curso</span>
                    </div>

                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cursos.store') }}" role="form">
                            @csrf

                            {{-- El partial incluye:
                                 - Materia, Profesor, Aula, Periodo
                                 - Día de la semana, Hora inicio, Hora fin
                                 - Bloque de error "horario" por trigger --}}
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

