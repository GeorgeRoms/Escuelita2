@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Datos personales</h3>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th style="width: 200px;">No. Control:</th>
                        <td>{{ $alumno->no_control }}</td>
                    </tr>
                    <tr>
                        <th>Semestre:</th>
                        <td>{{ $alumno->semestre ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <td>
                            {{ trim(($alumno->nombre ?? '') . ' ' . ($alumno->apellido_pat ?? '') . ' ' . ($alumno->apellido_mat ?? '')) }}
                        </td>
                    </tr>
                    <tr>
                        <th>Correo:</th>
                        <td>{{ $correo ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono:</th>
                        <td>{{ $telefono ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Dirección:</th>
                        <td>{{ $direccion ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Estatus:</th>
                        <td>{{ $estatus ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
