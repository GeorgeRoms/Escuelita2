<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReporteCarreraPeriodoController extends Controller
{

    public function pdf(Request $request)
{
    // 1) Lee filtros
    $carrera   = $request->query('carrera');
    $anio      = $request->query('anio');            // lee tal cual
    $periodo   = $request->query('periodo');         // 'Enero-Junio' | 'Agosto-Diciembre'
    $periodoId = $request->query('periodo_id');

    // normaliza año
    $anio = is_numeric($anio) ? (int)$anio : null;

    // 2) Si no vienen anio/periodo pero sí periodo_id, resuélvelo
    if ((!$anio || !$periodo) && $periodoId) {
        $per = DB::table('periodos')->where('id', $periodoId)->first(['anio','nombre']);
        if ($per) {
            $anio    = (int)$per->anio;
            $periodo = $per->nombre;
        }
    }

    // 3) Validación final
    if (!$carrera || !$anio || !$periodo) {
         return back()->withErrors('Faltan filtros para generar el PDF (carrera, año, periodo).');
     }

    Log::debug('PDF params', ['carrera'=>$carrera,'anio'=>$anio,'periodo'=>$periodo]);
    //dd($carrera, $anio, $periodo); // prueba exprés: deben verse con valores reales

    // === Llamada al SP (asegúrate de tener el SP actualizado) ===
    $stmt = DB::getPdo()->prepare('CALL resumen_carrera_periodo(?, ?, ?)');
    $stmt->execute([$carrera, $anio, $periodo]);

    $kpis       = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt->nextRowset(); $porMateria  = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt->nextRowset(); $porProfesor = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt->nextRowset(); $porAlumno   = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    // Logo base64 (MIME correcto)
    $logoB64 = null;
    $logoPath = public_path('images/escuelita-logo.png');
    if (is_file($logoPath)) {
        $mime = mime_content_type($logoPath) ?: 'image/png';
        $logoB64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoPath));
    }

    $data = [
        'carrera'         => $carrera,
        'anio'            => $anio,
        'periodoNombre'   => $periodo,
        'periodoEtiqueta' => "{$periodo} {$anio}",
        'kpis'            => $kpis,
        'porMateria'      => $porMateria,
        'porProfesor'     => $porProfesor,
        'porAlumno'       => $porAlumno,
        'logoB64'         => $logoB64,
        'generado'        => now()->format('d/m/Y H:i'),
    ];

    $pdf = Pdf::loadView('reportes.carrera_periodo_pdf', $data)
              ->setPaper('a4', 'landscape');

    return $pdf->download("resumen_{$carrera}_{$periodo}_{$anio}.pdf");
}

    public function ver(Request $request)
    {
        $request->validate([
            'carrera'    => ['required','string'],
            'periodo_id' => ['required','integer','min:1'],
        ]);

        $carrera   = $request->carrera;
        $periodoId = (int) $request->periodo_id;

        // Obtener anio y nombre del periodo a partir del id seleccionado
        $per = DB::table('periodos')->where('id', $periodoId)->first(['anio','nombre']);
        if (!$per) {
            return back()->with('error', 'Periodo inválido.');
        }

        // Llamar SP con PDO para leer múltiples result sets
        $pdo  = DB::getPdo();
        $stmt = $pdo->prepare("CALL resumen_carrera_periodo(:carrera, :anio, :nombre)");
        $stmt->bindValue(':carrera', $carrera);
        $stmt->bindValue(':anio',    $per->anio, \PDO::PARAM_INT);
        $stmt->bindValue(':nombre',  $per->nombre);
        $stmt->execute();

        // RS1 KPIs
        $kpis = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // RS2 por materia
        $stmt->nextRowset();
        $porMateria = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // RS3 por profesor
        $stmt->nextRowset();
        $porProfesor = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // RS4 detalle por alumno
        $stmt->nextRowset();
        $porAlumno = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        // Encabezado bonito
        $periodoEtiqueta = "{$per->nombre} {$per->anio}";

        return view('reportes.carrera-periodo-ver', [
            'carrera'         => $carrera,
            'periodoEtiqueta' => "{$per->nombre} {$per->anio}",
            'kpis'            => $kpis,
            'porMateria'      => $porMateria,
            'porProfesor'     => $porProfesor,
            'porAlumno'       => $porAlumno,
            'anio'            => (int) $per->anio,   // 👈 indispensables para el botón
            'periodo'         => $per->nombre,       // 👈 indispensables para el botón
        ]);
    }
}