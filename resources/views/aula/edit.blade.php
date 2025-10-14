@extends('layouts.app')

@section('template_title')
    {{ __('Actualizar información del') }} Aula
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Actualizar información del') }} Aula</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('aulas.update', $aula->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('aula.form')

                        </form>
                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="aulas.index" label="Cancelar" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
