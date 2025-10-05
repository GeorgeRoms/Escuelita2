@extends('layouts.app')

@section('template_title')
    Kardexes
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Kardexes') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('kardexes.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Id Kardex</th>
									<th >Fk Alumno</th>
									<th >Fk Curso</th>
									<th >Fecha Inscri</th>
									<th >Estado</th>
									<th >Promedio</th>
									<th >Oportunidad</th>
									<th >Intento</th>
									<th >Semestre</th>
									<th >Unidades Reprobadas</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kardexes as $kardex)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $kardex->id_kardex }}</td>
										<td >{{ $kardex->fk_alumno }}</td>
										<td >{{ $kardex->fk_curso }}</td>
										<td >{{ $kardex->fecha_inscri }}</td>
										<td >{{ $kardex->estado }}</td>
										<td >{{ $kardex->promedio }}</td>
										<td >{{ $kardex->oportunidad }}</td>
										<td >{{ $kardex->intento }}</td>
										<td >{{ $kardex->semestre }}</td>
										<td >{{ $kardex->unidades_reprobadas }}</td>

                                            <td>
                                                <form action="{{ route('kardexes.destroy', $kardex->id_kardex) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('kardexes.show', $kardex->id_kardex) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('kardexes.edit', $kardex->id_kardex) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $kardexes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
