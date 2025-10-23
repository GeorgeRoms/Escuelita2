<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Carrera; // ⬅️ importa el modelo para el catálogo
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteEspecialController extends Controller
{
    public function especialPdf(Request $request)
{
    $carreras = Carrera::orderBy('nombre_carr')->pluck('nombre_carr', 'nombre_carr');
    $carrera = $request->query('carrera', $carreras->keys()->first());
    $intento = $request->query('intento', 'Especial');
    if (!in_array($intento, ['Normal','Repite','Especial'], true)) $intento = 'Especial';

    // SP con 2 parámetros (asegúrate de tener la versión nueva)
    $stmt = DB::getPdo()->prepare('CALL resumen_especial_por_carrera(?, ?)');
    $stmt->execute([$carrera, $intento]);
    $rs1 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt->nextRowset(); $rs2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt->nextRowset(); $rs3 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    $stmt->nextRowset(); $rs4 = $stmt->fetchAll(\PDO::FETCH_ASSOC);
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
        'intento'         => $intento,
        'totAlumnos'      => $rs1[0]['total_alumnos_especial'] ?? 0,
        'materiasDetalle' => $rs2,
        'totMaterias'     => $rs3[0]['total_materias_en_especial'] ?? 0,
        'alumnosDetalle'  => $rs4,
        'hoy'             => now()->format('d/m/Y H:i'),
        'logoB64'         => $logoB64,
    ];

    $pdf = Pdf::loadView('reportes.especial_pdf', $data)
              ->setPaper('a4', 'portrait'); // 'landscape' si lo prefieres

    // Para descargar:
    return $pdf->download("reporte_{$carrera}_{$intento}.pdf");

    // O para ver en el navegador:
    // return $pdf->stream("reporte_{$carrera}_{$intento}.pdf");
}   

    public function index(Request $request)
{
    $carreras = Carrera::orderBy('nombre_carr')
        ->pluck('nombre_carr', 'nombre_carr'); // value = texto

    $carrera = $request->input('carrera', $carreras->keys()->first()); // default
    $intento = $request->input('intento', 'Especial');

    return view('reportes.index', compact('carreras','carrera','intento'));
}
    public function resumenPorCarrera(Request $request)
    {
        // Catálogo de carreras para el select
        $carreras = Carrera::orderBy('nombre_carr')->pluck('nombre_carr', 'nombre_carr');

        // Valores seleccionados (por defecto: primera carrera y 'Especial')
        $carrera = $request->input('carrera', $carreras->keys()->first());
        $intento = $request->input('intento', 'Especial'); // Normal | Repite | Especial

        // Seguridad básica por si llega algo raro en intento
        if (!in_array($intento, ['Normal','Repite','Especial'], true)) {
            $intento = 'Especial';
        }

        // Llamada al SP con 2 parámetros (p_carrera, p_intento)
        $pdo  = DB::getPdo();
        $stmt = $pdo->prepare("CALL resumen_especial_por_carrera(?, ?)");
        $stmt->execute([$carrera, $intento]);

        $rs1 = $stmt->fetchAll(\PDO::FETCH_ASSOC);   // total alumnos
        $stmt->nextRowset();
        $rs2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);   // materias detalle
        $stmt->nextRowset();
        $rs3 = $stmt->fetchAll(\PDO::FETCH_ASSOC);   // total materias
        $stmt->nextRowset();
        $rs4 = $stmt->fetchAll(\PDO::FETCH_ASSOC);   // detalle por alumno
        $stmt->closeCursor();

        return view('reportes.especial', [
            'carreras'        => $carreras,                 // ⬅️ para el select
            'carrera'         => $carrera,                  // seleccionado
            'intento'         => $intento,                  // seleccionado
            'totAlumnos'      => $rs1[0]['total_alumnos_especial'] ?? 0,
            'materiasDetalle' => $rs2,
            'totMaterias'     => $rs3[0]['total_materias_en_especial'] ?? 0,
            'alumnosDetalle'  => $rs4,
        ]);
    }
}


