@extends('layouts.app')

@section('template_title')
    Areas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Areas') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('areas.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Id Area</th>
									<th >Nombre Area</th>
									<th >Fk Edificio</th>
									<th >Fk Jefe</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($areas as $area)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $area->id_area }}</td>
										<td >{{ $area->nombre_area }}</td>
										<td >{{ $area->fk_edificio }}</td>
										<td >{{ $area->fk_jefe }}</td>

                                            <td>
                                                <form action="{{ route('areas.destroy', $area->id_area) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('areas.show', $area->id_area) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('areas.edit', $area->id_area) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $areas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
