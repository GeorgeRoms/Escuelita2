@extends('layouts.app')

@section('template_title')
    Profesores
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Profesores') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('profesores.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Id Profesor</th>
									<th >Nombre</th>
									<th >Apellido Pat</th>
									<th >Apellido Mat</th>
									<th >Area</th>
									<th >Tipo</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($profesores as $profesore)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $profesore->id_profesor }}</td>
										<td >{{ $profesore->nombre }}</td>
										<td >{{ $profesore->apellido_pat }}</td>
										<td >{{ $profesore->apellido_mat }}</td>
										<td >{{ $profesore->area }}</td>
										<td >{{ $profesore->tipo }}</td>

                                            <td>
                                                <form action="{{ route('profesores.destroy', $profesore->id_profesor) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('profesores.show', $profesore->id_profesor) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('profesores.edit', $profesore->id_profesor) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $profesores->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
