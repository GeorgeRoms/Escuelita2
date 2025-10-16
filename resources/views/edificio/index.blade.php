@extends('layouts.app')

@section('template_title')
    Edificios
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Edificios') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('edificios.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                    {{ __('Registrar edificio') }}
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
                                    <th >Codigo</th>
                                    <th >Nombre</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($edificios as $edificio)
                                        <tr>
                                        <td >{{ $edificio->codigo }}</td>
                                        <td >{{ $edificio->nombre }}</td>

                                            <td>
                                                <form action="{{ route('edificios.destroy', $edificio->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('edificios.show', $edificio->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('edificios.edit', $edificio->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de eliminar edificio?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
                                @if ($edificios->onFirstPage())
                                    <button class="btn btn-sm btn-primary text-white disabled me-2" disabled><i class="fa fa-fw fa-arrow-left"></i> {{ __('Anterior') }}</button>
                                @else
                                    <a href="{{ $edificios->previousPageUrl() }}" class="btn btn-sm btn-primary text-white me-2"><i class="fa fa-fw fa-arrow-left"></i> {{ __('Anterior') }}</a>
                                @endif
                                
                                {{-- Enlace Siguiente (Next) --}}
                                @if ($edificios->hasMorePages())
                                    <a href="{{ $edificios->nextPageUrl() }}" class="btn btn-sm btn-primary text-white">{{ __('Siguiente') }} <i class="fa fa-fw fa-arrow-right"></i></a>
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
                {{-- {!! $edificios->withQueryString()->links() !!} --}}
            </div>
        </div>
    </div>
@endsection
