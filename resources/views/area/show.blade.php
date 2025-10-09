@extends('layouts.app')

@section('template_title')
    {{ $area->name ?? __('Información del') . " " . __('area') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Información del') }} area</span>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Código:</strong>
                                    {{ $area->id_area }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre de area:</strong>
                                    {{ $area->nombre_area }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Edificio / Salón:</strong>
                                    @php($e = $area->edificio ?? null)
                                    {{ $e ? ($e->salon ?? $e->edificio ?? $area->fk_edificio) : $area->fk_edificio }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Jefe de área:</strong>
                                    @php($j = $area->jefe ?? null)
                                    {{ $j ? trim($j->nombre.' '.$j->apellido_pat.' '.($j->apellido_mat ?? '')) : '—' }}
                                </div>

                    </div>
                    <div class="d-flex gap-2">
                        <x-back to="areas.index" label="Atrás" style="margin-left: 1.5%; margin-top: -0.5%; margin-bottom: 1%"/>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
