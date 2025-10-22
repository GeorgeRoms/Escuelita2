{{-- resources/views/reportes/especial.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  // Defaults por si el controlador no envía algo
  $intento = $intento ?? 'Especial';
  $carrera = $carrera ?? '—';
  $intentoNice = ucfirst(strtolower($intento)); // Normal | Repite | Especial
@endphp

<div class="container">
  <h3>Resumen “{{ $intentoNice }}” — {{ $carrera }}</h3>

  <div class="row my-3">
    <div class="col-md-4">
      <div class="card"><div class="card-body">
        <h5 class="card-title">Alumnos en “{{ $intentoNice }}”</h5>
        <p class="display-6 mb-0">{{ $totAlumnos }}</p>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card"><div class="card-body">
        <h5 class="card-title">Materias con “{{ $intentoNice }}”</h5>
        <p class="display-6 mb-0">{{ $totMaterias }}</p>
      </div></div>
    </div>
  </div>

  <h5>Detalle por materia</h5>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Materia</th>
          <th>Alumnos en “{{ $intentoNice }}”</th>
        </tr>
      </thead>
      <tbody>
        @forelse($materiasDetalle as $row)
          <tr>
            <td>{{ $row['id_materia'] }}</td>
            <td>{{ $row['materia'] }}</td>
            {{-- El SP dejó el alias como alumnos_en_especial; lo reutilizamos --}}
            <td>{{ $row['alumnos_en_especial'] }}</td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-muted">Sin registros</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <h5 class="mt-4">Detalle por alumno</h5>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>No. control</th>
          <th>Alumno</th>
          <th>Semestre</th>
          <th>Carrera</th>
          <th>Materias en “{{ $intentoNice }}”</th>
        </tr>
      </thead>
      <tbody>
        @forelse($alumnosDetalle as $row)
          <tr>
            <td>{{ $row['no_control'] }}</td>
            <td>{{ $row['alumno'] }}</td>
            <td>{{ $row['semestre'] }}</td>
            <td>{{ $row['carrera'] }}</td>
            <td>{{ $row['materias_en_especial'] }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-muted">
              Sin alumnos en “{{ $intentoNice }}” para esta carrera.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary mt-2">← Atrás</a>
</div>
@endsection

