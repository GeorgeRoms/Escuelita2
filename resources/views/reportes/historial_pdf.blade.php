<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Historial académico — {{ $alumnoInfo->no_control }}</title>
<style>
  @page { margin: 15mm; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
  h1,h2,h3,h4 { margin: 0 0 8px; }
  .meta { color:#555; font-size:11px; margin-bottom:10px; }
  table { width:100%; border-collapse: collapse; margin-top:8px; }
  th, td { border:1px solid #ccc; padding:6px 8px; }
  th { background:#f3f3f3; text-align:left; }
  .text-right { text-align: right; }
</style>
</head>
<div class="header">
  <div class="h-left">
    @php $logo = $logoBase64 ?? ($logoB64 ?? null); @endphp
    @if($logo)
      <img src="{{ $logo }}" alt="Logo" style="width:70px; height:auto;">
    @endif
  </div>
<body>
  <h4>Historial académico del alumno</h4>
  <div class="meta">
    <strong>No. Control:</strong> {{ $alumnoInfo->no_control }} ·
    <strong>Nombre:</strong> {{ $alumnoInfo->nombre_completo }} ·
    Generado: {{ $fecha }}
  </div>

  <table>
    <thead>
      <tr>
        <th>Materia</th>
        <th>Profesor</th>
        <th>Periodo</th>
        <th>Estado</th>
        <th>Intento</th>
        <th class="text-right">Promedio</th>
        <th>Semestre</th>
        <th>Código del Curso</th>
      </tr>
    </thead>
    <tbody>
      @forelse($historial as $h)
        <tr>
          <td>{{ $h->materia }}</td>
          <td>{{ $h->profesor }}</td>
          <td>{{ $h->periodo }}</td>
          <td>{{ $h->estado }}</td>
          <td>{{ $h->intento }}</td>
          <td class="text-right">
            {{ $h->promedio !== null ? number_format($h->promedio, 2) : '—' }}
          </td>
          <td>{{ $h->semestre }}</td>
          <td>{{ $h->id_curso }}</td>
        </tr>
      @empty
        <tr><td colspan="8">Sin materias registradas.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
