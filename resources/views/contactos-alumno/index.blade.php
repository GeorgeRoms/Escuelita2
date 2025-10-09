@extends('layouts.app')

@section('template_title')
    Contactos Alumnos
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Contactos Alumnos') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('contactos-alumnos.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Registrar contacto') }}
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
									<th >Id Contacto</th>
									<th >Correo</th>
									<th >Telefono</th>
									<th >Dirección</th>
                                    <th>Alumno</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contactosAlumnos as $contactosAlumno)
                                        <tr>
										<td >{{ $contactosAlumno->id_contacto }}</td>
										<td >{{ $contactosAlumno->correo }}</td>
										<td >{{ $contactosAlumno->telefono }}</td>
										<td >{{ $contactosAlumno->direccion }}</td>
                                        <td>
                                            @php($a = $contactosAlumno->alumno)
                                            {{ $a ? $a->no_control.' — '.trim($a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? '')) : '—' }}
                                        </td>

                                            <td>
                                                <form action="{{ route('contactos-alumnos.destroy', $contactosAlumno->id_contacto) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('contactos-alumnos.show', $contactosAlumno->id_contacto) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('contactos-alumnos.edit', $contactosAlumno->id_contacto) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('¿Está seguro de querer borrar este contacto?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
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
                {!! $contactosAlumnos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
