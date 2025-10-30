<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Alumnos por Curso #{{ $cursoInfo->id_curso ?? '' }}</title>
<style>
  @page { margin: 15mm; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
  h1,h2,h3,h4 { margin: 0 0 8px; }
  .meta { color:#555; font-size:11px; margin-bottom:10px; }
  table { width:100%; border-collapse:collapse; margin-top:8px; }
  th,td { border:1px solid #ccc; padding:6px 8px; }
  th { background:#f3f3f3; text-align:left; }
</style>
</head>
<body>
  <div class="header">
  <div class="h-left">
    @php $logo = $logoBase64 ?? ($logoB64 ?? null); @endphp
    @if($logo)
      <img src="{{ $logo }}" alt="Logo" style="width:70px; height:auto;">
    @endif
  </div>
  <h3>Reporte — Alumnos por Curso</h3>
  <div class="meta">
    Generado el {{ $fecha }}
  </div>

  @if($cursoInfo)
  <p>
    <strong>Curso #:</strong> {{ $cursoInfo->id_curso ?? '' }} ·
    <strong>Materia:</strong> {{ $cursoInfo->materia ?? '' }} ·
    <strong>Periodo:</strong> {{ $cursoInfo->periodo ?? '' }} ·
    <strong>Profesor:</strong> {{ $cursoInfo->profesor ?? '' }}
  </p>
  @endif

  <table>
    <thead>
      <tr>
        <th>No. Control</th>
        <th>Alumno</th>
        <th>Semestre</th>
        <th>Intento</th>
        <th>Calificación</th>
        <th>Resultado</th>
      </tr>
    </thead>
    <tbody>
      @forelse($alumnos as $row)
        <tr>
          <td>{{ $row->no_control }}</td>
          <td>{{ $row->alumno }}</td>
          <td>{{ $row->semestre }}</td>
          <td>{{ $row->intento }}</td>
          <td>{{ number_format($row->calificacion, 2) }}</td>
          <td>{{ $row->resultado }}</td>
        </tr>
      @empty
        <tr><td colspan="7">Sin inscripciones para este curso.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
