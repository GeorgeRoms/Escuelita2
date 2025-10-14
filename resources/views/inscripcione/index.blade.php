@extends('layouts.app')

@section('template_title')
    Inscripciones
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Inscripciones') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('inscripciones.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Alumno No Control</th>
									<th >Curso Id</th>
									<th >Estado</th>
									<th >Oportunidad</th>
									<th >Intento</th>
									<th >Semestre</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inscripciones as $inscripcione)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $inscripcione->alumno_no_control }}</td>
										<td >{{ $inscripcione->curso_id }}</td>
										<td >{{ $inscripcione->estado }}</td>
										<td >{{ $inscripcione->oportunidad }}</td>
										<td >{{ $inscripcione->intento }}</td>
										<td >{{ $inscripcione->semestre }}</td>

                                            <td>
                                                <form action="{{ route('inscripciones.destroy', $inscripcione->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('inscripciones.show', $inscripcione->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('inscripciones.edit', $inscripcione->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $inscripciones->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
