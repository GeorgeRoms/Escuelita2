<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Materias impartidas por profesor</title>
<style>
  @page { margin: 15mm; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
  h1,h2,h3,h4 { margin: 0 0 8px; }
  .meta { color:#555; font-size:11px; margin-bottom:10px; }
  table { width:100%; border-collapse: collapse; margin-top:8px; }
  th, td { border:1px solid #ccc; padding:6px 8px; }
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
  <h4>Materias impartidas por profesor</h4>
  <div class="meta">
    <strong>Código de profesor:</strong> {{$id_profesor}}
    <strong>Docente:</strong> {{ $docente }}
    @if($periodoEtiqueta) · <strong>Periodo:</strong> {{ $periodoEtiqueta }} @endif
    · Generado: {{ $fecha }}
  </div>

  <table>
    <thead>
      <tr>
        <th>Código de profesor</th>

        <th>Código de curso</th>
        <th>Materia</th>
        
        <th>Alumnos inscritos</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $r)
        <tr>
          <td>{{ $r->id_profesor }}</td>

          <td>{{ $r->id_curso }}</td>
          <td>{{ $r->materia }}</td>
          
          <td>{{ $r->alumnos_inscritos }}</td>
        </tr>
      @empty
        <tr><td colspan="6">Sin cursos para los filtros seleccionados.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
