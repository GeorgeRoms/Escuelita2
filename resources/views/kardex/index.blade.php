@extends('layouts.app')

@section('template_title')
    Kardex de alumnos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Kardex de alumnos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('kardexes.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Registrar Kardex') }}
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
									<th>Código de kardex</th>
                                    <th>Alumno</th>
                                    <th>Materia (Curso)</th>
                                    <th>Estado</th>
                                    <th>Promedio</th>
                                    <th>Semestre</th>
                                    <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kardexes as $kardex)
                                        <tr>
										<td >{{ $kardex->id_kardex }}</td>
										<td >
                                            @if($kardex->alumno)
                                            {{ $kardex->alumno->no_control }} — {{ trim($kardex->alumno->nombre.' '.$kardex->alumno->apellido_pat.' '.($kardex->alumno->apellido_mat ?? '')) }}
                                            @else
                                            {{ $kardex->fk_alumno }}
                                            @endif
                                        </td>
										<td >{{ $kardex->curso->materia->nombre_mat ?? '—' }} ({{ $kardex->fk_curso }})</td>
										<td >{{ $kardex->estado }}</td>
										<td >{{ $kardex->promedio }}</td>
										<td >{{ $kardex->semestre }}</td>
                                            <td>
                                                <form action="{{ route('kardexes.destroy', $kardex->id_kardex) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('kardexes.show', $kardex->id_kardex) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('kardexes.edit', $kardex->id_kardex) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de querer borrar kardex?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
                {!! $kardexes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
