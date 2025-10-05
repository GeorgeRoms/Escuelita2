@extends('layouts.app')

@section('template_title')
    Contactos Profesores
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Contactos Profesores') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('contactos-profesores.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
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
                                        <th>No</th>
                                        
									<th >Id Contacto</th>
									<th >Correo</th>
									<th >Telefono</th>
									<th >Direccion</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contactosProfesores as $contactosProfesore)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $contactosProfesore->id_contacto }}</td>
										<td >{{ $contactosProfesore->correo }}</td>
										<td >{{ $contactosProfesore->telefono }}</td>
										<td >{{ $contactosProfesore->direccion }}</td>

                                            <td>
                                                <form action="{{ route('contactos-profesores.destroy', $contactosProfesore->id_contacto) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('contactos-profesores.show', $contactosProfesore->id_contacto) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('contactos-profesores.edit', $contactosProfesore->id_contacto) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $contactosProfesores->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
