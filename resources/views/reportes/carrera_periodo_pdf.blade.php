<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Resumen — {{ $carrera }} ({{ $periodoEtiqueta }})</title>
<style>
  @page { margin: 15mm; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
  h1,h2,h3,h4 { margin: 0 0 8px; }
  .header { display: table; width: 100%; margin-bottom: 10px; }
  .h-left { display: table-cell; vertical-align: middle; width: 80px; }
  .h-right { display: table-cell; vertical-align: middle; }
  .muted { color:#666; font-size:11px; }
  table { width:100%; border-collapse: collapse; margin-top: 8px; }
  th, td { border: 1px solid #ccc; padding: 6px 8px; }
  th { background: #f3f3f3; text-align: left; }
  .grid { display: table; width:100%; margin: 8px 0 12px; }
  .col { display: table-cell; width:25%; padding-right:8px; }
  .card { border:1px solid #ddd; padding:8px; border-radius:4px; }
  .k { color:#777; font-size:11px; }
  .v { font-size:18px; margin-top:2px; }
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
  @php $k = $kpis[0] ?? null; @endphp
  <div class="grid">
    <div class="col"><div class="card">
      <div class="k">Alumnos únicos</div><div class="v">{{ $k['alumnos_unicos'] ?? 0 }}</div>
    </div></div>
    <div class="col"><div class="card">
      <div class="k">Inscripciones</div><div class="v">{{ $k['total_inscripciones'] ?? 0 }}</div>
    </div></div>
    <div class="col"><div class="card">
      <div class="k">Materias</div><div class="v">{{ $k['materias_distintas'] ?? 0 }}</div>
    </div></div>
    <div class="col"><div class="card">
      <div class="k">Profesores</div><div class="v">{{ $k['profesores_distintos'] ?? 0 }}</div>
    </div></div>
  </div>

  <div class="grid">
    <div class="col"><div class="card">
      <div class="k">Intentos Normal</div><div class="v">{{ $k['intentos_normal'] ?? 0 }}</div>
    </div></div>
    <div class="col"><div class="card">
      <div class="k">Intentos Repite</div><div class="v">{{ $k['intentos_repite'] ?? 0 }}</div>
    </div></div>
    <div class="col"><div class="card">
      <div class="k">Intentos Especial</div><div class="v">{{ $k['intentos_especial'] ?? 0 }}</div>
    </div></div>
    <div class="col"></div>
  </div>

  <h4>Alumnos por materia</h4>
  <table>
    <thead><tr><th>ID Materia</th><th>Materia</th><th>Alumnos inscritos</th></tr></thead>
    <tbody>
      @forelse($porMateria as $r)
        <tr>
          <td>{{ $r['id_materia'] }}</td>
          <td>{{ $r['materia'] }}</td>
          <td>{{ $r['alumnos_inscritos'] }}</td>
        </tr>
      @empty
        <tr><td colspan="3">Sin datos.</td></tr>
      @endforelse
    </tbody>
  </table>

  <h4 style="margin-top:12px">Alumnos por profesor</h4>
  <table>
    <thead><tr><th>ID Profesor</th><th>Profesor</th><th>Alumnos inscritos</th></tr></thead>
    <tbody>
      @forelse($porProfesor as $r)
        <tr>
          <td>{{ $r['id_profesor'] }}</td>
          <td>{{ $r['profesor'] }}</td>
          <td>{{ $r['alumnos_inscritos'] }}</td>
        </tr>
      @empty
        <tr><td colspan="3">Sin datos.</td></tr>
      @endforelse
    </tbody>
  </table>

  <h4 style="margin-top:12px">Detalle por alumno</h4>
  <table>
    <thead>
      <tr>
        <th>No. control</th>
        <th>Alumno</th>
        <th>Semestre</th>
        <th># Inscripciones</th>
        <th>Especial</th>
        <th>Repite</th>
      </tr>
    </thead>
    <tbody>
      @forelse($porAlumno as $r)
        <tr>
          <td>{{ $r['no_control'] }}</td>
          <td>{{ $r['alumno'] }}</td>
          <td>{{ $r['semestre'] }}</td>
          <td>{{ $r['inscripciones_en_periodo'] }}</td>
          <td>{{ $r['especiales'] }}</td>
          <td>{{ $r['repite'] }}</td>
        </tr>
      @empty
        <tr><td colspan="6">Sin alumnos.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
