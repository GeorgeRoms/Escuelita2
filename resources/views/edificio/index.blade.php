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
                                {{ __('Edificios/Salones') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('edificios.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Registrar Edificio/Salón') }}
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
									<th >Edificio</th>
									<th >Salon</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($edificios as $edificio)
                                        <tr>
										<td >{{ $edificio->edificio }}</td>
										<td >{{ $edificio->salon }}</td>

                                            <td>
                                                <form action="{{ route('edificios.destroy', $edificio->edificio) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('edificios.show', $edificio->edificio) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('edificios.edit', $edificio->edificio) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex gap-2">
                        <x-back label="Atrás" style="margin-top: -0.5%; margin-bottom: 1%"/>
                        </div>
                    </div>
                </div>
                {!! $edificios->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
