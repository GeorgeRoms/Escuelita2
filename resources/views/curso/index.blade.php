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
                                <a href="{{ route('cursos.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
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
                                        <th>Código de curso</th>
                                        <th>Cupo</th>
                                        <th>Materia</th>
                                        <th>Profesor</th>
                                        <th>Aula</th>
                                        <th>Periodo</th>
                                        {{-- NUEVOS CAMPOS --}}
                                        <th>Día</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cursos as $curso)
                                        @php
                                            $p = $curso->profesor;
                                            $a = $curso->aula;
                                            $e = $a?->edificio;
                                            $periodoLabel = $curso->periodo ? ($curso->periodo->nombre.' '.$curso->periodo->anio) : '—';
                                        @endphp
                                        <tr>
                                            <td>{{ $curso->id_curso }}</td>
                                            <td>{{ $curso->cupo }}</td>
                                            <td>{{ $curso->materia->nombre_mat ?? '—' }}</td>
                                            <td>
                                                {{ $p ? trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? '')) : '—' }}
                                            </td>
                                            <td>{{ $a && $e ? ($e->codigo.' - '.$a->salon) : '—' }}</td>
                                            <td>{{ $periodoLabel }}</td>

                                            {{-- NUEVOS CAMPOS --}}
                                            <td>{{ $curso->dia_semana ?? '—' }}</td>
                                            <td>{{ optional($curso->hora_inicio)->format('H:i') ?? '—' }}</td>
                                            <td>{{ optional($curso->hora_fin)->format('H:i') ?? 'H:i' ? optional($curso->hora_fin)->format('H:i') : '—' }}</td>

                                            <td>
                                                <form action="{{ route('cursos.destroy', $curso->id_curso) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('cursos.show', $curso->id_curso) }}">
                                                        <i class="fa fa-fw fa-eye"></i> {{ __('Mostrar') }}
                                                    </a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cursos.edit', $curso->id_curso) }}">
                                                        <i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}
                                                    </a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="event.preventDefault(); confirm('¿Está seguro de querer cancelar el curso?') ? this.closest('form').submit() : false;">
                                                        <i class="fa fa-fw fa-trash"></i> {{ __('Cancelar') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- INICIO: Botones de Paginación Anterior/Siguiente --}}
                        <div class="d-flex justify-content-center align-items-center mt-3 p-2">
                            <div class="d-flex align-items-center">
                                {{-- Enlace Anterior (Previous) --}}
                                @if ($cursos->onFirstPage())
                                    <button class="btn btn-sm btn-primary text-white disabled me-2" disabled>
                                        <i class="fa fa-fw fa-arrow-left"></i> {{ __('Anterior') }}
                                    </button>
                                @else
                                    <a href="{{ $cursos->previousPageUrl() }}" class="btn btn-sm btn-primary text-white me-2">
                                        <i class="fa fa-fw fa-arrow-left"></i> {{ __('Anterior') }}
                                    </a>
                                @endif

                                {{-- Enlace Siguiente (Next) --}}
                                @if ($cursos->hasMorePages())
                                    <a href="{{ $cursos->nextPageUrl() }}" class="btn btn-sm btn-primary text-white">
                                        {{ __('Siguiente') }} <i class="fa fa-fw fa-arrow-right"></i>
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-primary text-white disabled" disabled>
                                        {{ __('Siguiente') }} <i class="fa fa-fw fa-arrow-right"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        {{-- FIN: Botones de Paginación Anterior/Siguiente --}}

                        <div class="d-flex gap-2">
                            <x-back label="Atrás" style="margin-top: -0.5%; margin-bottom: 1%"/>
                        </div>
                    </div>
                </div>
                {{-- ELIMINADO: Se quitó el paginador estándar de Laravel que causaba las flechas grandes --}}
                {{-- {!! $cursos->withQueryString()->links() !!} --}}
            </div>
        </div>
    </div>
@endsection