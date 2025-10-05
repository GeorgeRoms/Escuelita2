@extends('layouts.app')

@section('template_title')
    Historials
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Historials') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('historials.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Fk Alumno</th>
									<th >Fecha Apertura</th>
									<th >Observaciones</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historials as $historial)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $historial->fk_alumno }}</td>
										<td >{{ $historial->fecha_apertura }}</td>
										<td >{{ $historial->observaciones }}</td>

                                            <td>
                                                <form action="{{ route('historials.destroy', $historial->fk_alumno) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('historials.show', $historial->fk_alumno) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('historials.edit', $historial->fk_alumno) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $historials->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
