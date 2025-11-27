@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Plan de Materias</h3>

    <table class="table">
        <thead>
            <tr>
                <th>Materia</th>
                <th>Semestre</th>
            </tr>
        </thead>
        <tbody>
            @foreach($materias as $m)
            <tr>
                <td>{{ $m->nombre_mat }}</td>
                <td>{{ $m->semestre ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
