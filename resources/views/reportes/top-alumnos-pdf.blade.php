<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Top 10 — {{ $carrera }}</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ddd; padding: 6px; }
    th { background: #f2f2f2; }
    .hdr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .logo { height: 38px; }
  </style>
</head>
<body>
  <div class="hdr">
    <div>
      <div><strong>Top 10 alumnos por promedio</strong></div>
      <div>Carrera: <strong>{{ $carrera }}</strong></div>
      <div>Periodo: <strong>{{ $periodo ?? 'Todos los periodos' }}</strong></div>
      <div>Fecha: {{ $fecha }}</div>
    </div>
    @if($logoB64)
      <img class="logo" src="{{ $logoB64 }}" alt="Logo">
    @endif
  </div>

  <table>
    <thead>
      <tr>
        <th>No. Control</th>
        <th>Alumno</th>
        <th>
          {{-- Si filtras por periodo, deja claro que es el promedio de ese periodo --}}
          {{ isset($periodo) && $periodo !== 'Todos los periodos' ? 'Promedio del periodo' : 'Promedio general' }}
        </th>
        <th>Cursos</th>
      </tr>
    </thead>
    <tbody>
      @foreach($rows as $r)
        <tr>
          <td>{{ $r->no_control }}</td>
          <td>{{ $r->alumno }}</td>
          <td><strong>{{ number_format($r->promedio_general, 2) }}</strong></td>
          <td>{{ $r->cursos_contemplados }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>

