@extends('layouts.app')

@section('content')
<div class="container">
  <h3 class="mb-3">Reporte — Alumnos por Curso</h3>

  <form action="{{ route('reportes.curso.ver') }}" method="get" class="row g-2">
    <div class="col-md-8">
      <label class="form-label">Selecciona el curso</label>
      <select name="curso_id" class="form-select" required>
        <option value="" selected disabled>— elige —</option>
        @foreach($cursos as $c)
          <option value="{{ $c->id_curso }}">{{ $c->etiqueta }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button class="btn btn-primary w-100" type="submit">Ver</button>
    </div>
  </form>
</div>
@endsection
