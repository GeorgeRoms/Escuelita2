@extends('layouts.app')

@section('template_title')
    {{ __('Update') }} Periodo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Periodo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('periodos.update', $periodo->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('periodo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
