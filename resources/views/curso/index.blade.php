@extends('layouts.app')

@section('template_title')
    Cursos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Cursos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('cursos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Registrar Curso') }}
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
									<th >Código de curso</th>
									<th >Cupo</th>
									<th >Materia</th>
									<th >Profesor</th>
									<th >Edificio</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cursos as $curso)
                                        <tr>
										<td >{{ $curso->id_curso }}</td>
										<td >{{ $curso->cupo }}</td>
										<td >{{ $curso->materia->nombre_mat ?? '—' }}</td>
										<td >@php $p = $curso->profesor; @endphp
                                            {{ $p ? trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? '')) : '—' }}
                                        </td>
										<td >{{ $curso->fk_edificio }}</td>

                                            <td>
                                                <form action="{{ route('cursos.destroy', $curso->id_curso) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cursos.show', $curso->id_curso) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cursos.edit', $curso->id_curso) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de querer borrar curso?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
                {!! $cursos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
