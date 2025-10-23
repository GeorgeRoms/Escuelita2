<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Reporte {{ ucfirst(strtolower($intento ?? 'Especial')) }} — {{ $carrera }}</title>
<style>
  @page { margin: 20mm; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
  h1,h2,h3 { margin: 0 0 6px 0; }
  .meta { color:#555; font-size: 11px; margin-bottom: 12px; }
  .kpis { width:100%; margin:10px 0 14px; }
  .kpis td { width:50%; border:1px solid #ddd; padding:10px; vertical-align:middle; }
  .kpis h3 { margin-bottom: 6px; }
  table { width:100%; border-collapse: collapse; margin: 10px 0 18px; }
  th,td { border:1px solid #ddd; padding:6px 8px; }
  th { background:#f2f4f7; text-align:left; }
  .page-break { page-break-before: always; }
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
  @php $intentoNice = ucfirst(strtolower($intento ?? 'Especial')); @endphp

  <h2>Resumen “{{ $intentoNice }}” — {{ $carrera }}</h2>
  <div class="meta">Generado: {{ $hoy }}</div>

  <table class="kpis">
    <tr>
      <td>
        <h3>Alumnos en “{{ $intentoNice }}”</h3>
        <div style="font-size:22px">{{ $totAlumnos }}</div>
      </td>
      <td>
        <h3>Materias con “{{ $intentoNice }}”</h3>
        <div style="font-size:22px">{{ $totMaterias }}</div>
      </td>
    </tr>
  </table>

  <h3>Detalle por materia</h3>
  <table>
    <thead><tr><th>ID</th><th>Materia</th><th>Alumnos en “{{ $intentoNice }}”</th></tr></thead>
    <tbody>
      @forelse($materiasDetalle as $row)
        <tr>
          <td>{{ $row['id_materia'] }}</td>
          <td>{{ $row['materia'] }}</td>
          <td>{{ $row['alumnos_en_especial'] }}</td>
        </tr>
      @empty
        <tr><td colspan="3">Sin registros</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="page-break"></div>

  <h3>Detalle por alumno</h3>
  <table>
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
        <tr><td colspan="5">Sin alumnos en “{{ $intentoNice }}”.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>
