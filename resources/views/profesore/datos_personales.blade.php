@extends('layouts.app')

@section('title','Escuelita | Datos personales docente')

@section('content')
<div class="container">
    <h3 class="mb-4">Datos personales</h3>

    <div class="card">
        <div class="card-body">
            <ul class="list-group list-group-flush">

                {{-- ID / Clave del profesor --}}
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-semibold">ID profesor:</span>
                    <span>{{ $profesor->id_profesor ?? 'N/A' }}</span>
                </li>

                {{-- Nombre completo --}}
                @php
                    $nombreCompleto = trim(
                        ($profesor->nombre ?? '') . ' ' .
                        ($profesor->apellido_pat ?? '') . ' ' .
                        ($profesor->apellido_mat ?? '')
                    );

                    if (!$nombreCompleto) {
                        $nombreCompleto = $user->name ?? 'N/A';
                    }
                @endphp
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-semibold">Nombre:</span>
                    <span>{{ $nombreCompleto }}</span>
                </li>

                {{-- Correo (del user) --}}
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-semibold">Correo:</span>
                    <span>{{ $user->email ?? 'N/A' }}</span>
                </li>

                {{-- Teléfono --}}
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-semibold">Teléfono:</span>
                    <span>{{ $contacto->telefono ?? 'N/A' }}</span>
                </li>

                {{-- Área / departamento (si tienes relación/columna) --}}
                @php
    // Intentamos obtener solo el nombre del área sin mostrar el JSON completo
    $nombreArea = null;

    if (isset($profesor->area)) {
        // Si es un modelo/objeto (relación)
        if (is_object($profesor->area)) {
            $nombreArea = $profesor->area->nombre_area
                ?? $profesor->area->nombre
                ?? null;
        }
        // Si viene como arreglo/JSON decodificado
        elseif (is_array($profesor->area)) {
            $nombreArea = $profesor->area['nombre_area']
                ?? $profesor->area['nombre']
                ?? null;
        }
        // Si es solo un string plano
        else {
            $nombreArea = $profesor->area;
        }
    }
@endphp

<li class="list-group-item d-flex justify-content-between">
    <span class="fw-semibold">Área:</span>
    <span>{{ $nombreArea ?: 'N/A' }}</span>
</li>



                {{-- Dirección --}}
                @php
                    $dirPartes = array_filter([
                        $contacto->calle ?? null,
                        $contacto->colonia ?? null,
                        isset($contacto->num_ext) ? 'Ext. '.$contacto->num_ext : null,
                        isset($contacto->num_int) ? 'Int. '.$contacto->num_int : null,
                        $contacto->cp ?? null,
                        $contacto->estado ?? null,
                        $contacto->pais ?? null,
                    ]);

                    $direccionCompleta = $dirPartes ? implode(', ', $dirPartes) : 'N/A';
                @endphp
                <li class="list-group-item d-flex justify-content-between">
                    <span class="fw-semibold">Dirección:</span>
                    <span class="text-end">{{ $direccionCompleta }}</span>
                </li>

            </ul>
        </div>
    </div>
    <br><a href="{{ route('home.profesor') }}" class="btn btn-outline-secondary mb-3">&larr; Volver</a>
</div>
@endsection
