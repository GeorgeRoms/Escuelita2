@extends('layouts.app')

@section('template_title')
    Alumnos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Alumnos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('alumnos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
									<th >No. de Control</th>
									<th >Nombre</th>
									<th >Apellido Paterno</th>
									<th >Apellido Materno</th>
									<th >Genero</th>
									<th >Carrera</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alumnos as $alumno)
                                        <tr>
										<td >{{ $alumno->no_control }}</td>
										<td >{{ $alumno->nombre }}</td>
										<td >{{ $alumno->apellido_pat }}</td>
										<td >{{ $alumno->apellido_mat }}</td>
										<td >{{ $alumno->genero }}</td>
										<td >{{ $alumno->carrera->nombre_carr ?? '—' }}</td>

                                            <td>
                                                <form action="{{ route('alumnos.destroy', $alumno->no_control) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('alumnos.show', $alumno->no_control) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('alumnos.edit', $alumno->no_control) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $alumnos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
