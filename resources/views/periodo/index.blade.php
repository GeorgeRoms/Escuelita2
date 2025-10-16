@extends('layouts.app')

@section('template_title')
    Periodos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Periodos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('periodos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                    {{ __('Registrar periodo') }}
                                </a>
                             </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                    <th >Año</th>
                                    <th >Nombre</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($periodos as $periodo)
                                        <tr>
                                        <td >{{ $periodo->anio }}</td>
                                        <td >{{ $periodo->nombre }}</td>

                                            <td>
                                                <form action="{{ route('periodos.destroy', $periodo->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('periodos.show', $periodo->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('periodos.edit', $periodo->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Estás seguro de eliminar el periodo?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- INICIO: Botones de Paginación Anterior/Siguiente --}}
                        <div class="d-flex justify-content-center align-items-center mt-3 p-2">
                            <div class="d-flex align-items-center">
                                
                                {{-- Enlace Anterior (Previous) --}}
                                @if ($periodos->onFirstPage())
                                    <button class="btn btn-sm btn-primary text-white disabled me-2" disabled><i class="fa fa-fw fa-arrow-left"></i> {{ __('Anterior') }}</button>
                                @else
                                    <a href="{{ $periodos->previousPageUrl() }}" class="btn btn-sm btn-primary text-white me-2"><i class="fa fa-fw fa-arrow-left"></i> {{ __('Anterior') }}</a>
                                @endif
                                
                                {{-- Enlace Siguiente (Next) --}}
                                @if ($periodos->hasMorePages())
                                    <a href="{{ $periodos->nextPageUrl() }}" class="btn btn-sm btn-primary text-white">{{ __('Siguiente') }} <i class="fa fa-fw fa-arrow-right"></i></a>
                                @else
                                    <button class="btn btn-sm btn-primary text-white disabled" disabled>{{ __('Siguiente') }} <i class="fa fa-fw fa-arrow-right"></i></button>
                                @endif
                            </div>
                        </div>
                        {{-- FIN: Botones de Paginación Anterior/Siguiente --}}

                        <div class="d-flex gap-2">
                        <x-back label="Atrás" style="margin-top: -0.5%; margin-bottom: 1%"/>
                        </div>
                    </div>
                </div>
                {{-- ELIMINADO: Se quitó el paginador estándar de Laravel que causaba las flechas grandes --}}
                {{-- {!! $periodos->withQueryString()->links() !!} --}}
            </div>
        </div>
    </div>
@endsection
