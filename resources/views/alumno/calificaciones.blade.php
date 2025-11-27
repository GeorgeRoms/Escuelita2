@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Calificaciones</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Calificación</th>
                <th>Intento</th>
            </tr>
        </thead>
        <tbody>
            @foreach($califs as $c)
            <tr>
                <td>{{ $c->curso->materia->nombre_mat }}</td>
                <td>{{ $c->calificacion ?? 'N/A' }}</td>
                <td>{{ $c->intento }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
