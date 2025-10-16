@extends('layouts.app')

@section('template_title') Carreras de alumnos @endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Carreras de alumnos') }}
                            </span>

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
									<th >Alumno</th>
									<th >Carrera</th>
									<th >Estatus</th>
									<th >Fecha de inicio</th>
									<th >Fecha de fin</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alumnoCarreras as $alumnoCarrera)
                                    @php
                                    $al = $alumnoCarrera->alumno;
                                    $ca = $alumnoCarrera->carrera;
                                    @endphp
                                        <tr>
										<td >{{ $al ? ($al->no_control.' — '.$al->nombre.' '.$al->apellido_pat.' '.($al->apellido_mat ?? '')) : '—' }}</td>
										<td >{{ $ca->nombre_carr ?? '—' }}</td>
										<td >
                                            <span class="badge text-bg-{{ $alumnoCarrera->estatus === 'Activo' ? 'success' : 'secondary' }}">
                                                {{ $alumnoCarrera->estatus }}
                                            </span>
                                        </td>
										<td >{{ $alumnoCarrera->fecha_inicio ?? '—' }}</td>
										<td >{{ $alumnoCarrera->fecha_fin ?? '—' }}</td>

                                            <td>
                                                <form action="{{ route('alumno-carreras.destroy', $alumnoCarrera->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('alumno-carreras.show', $alumnoCarrera->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('alumno-carreras.edit', $alumnoCarrera->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Eliminar esta asignación?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
                {!! $alumnoCarreras->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
