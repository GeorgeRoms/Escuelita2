<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class ReporteAlumnoController extends Controller
{



    public function historialPdf(Request $request)
    {
        $noControl = $request->query('no_control');

        // Llama al SP actualizado (incluye a.semestre e i.promedio)
        $stmt = DB::getPdo()->prepare('CALL historial_alumno(?)');
        $stmt->execute([$noControl]);
        $historial = $stmt->fetchAll(\PDO::FETCH_OBJ);
        $stmt->closeCursor();

        // Encabezado
        $alumnoInfo = (object)[
            'no_control'      => $noControl,
            'nombre_completo' => optional(collect($historial)->first())->alumno ?? '',
        ];

        // Logo base64 (MIME correcto)
    $logoB64 = null;
    $logoPath = public_path('images/escuelita-logo.png');
    if (is_file($logoPath)) {
        $mime = mime_content_type($logoPath) ?: 'image/png';
        $logoB64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoPath));
    }

        $data = [
            'alumnoInfo' => $alumnoInfo,
            'historial'  => $historial,
            'fecha'      => now()->format('d/m/Y H:i'),
            'logoB64'         => $logoB64,
        ];

        $pdf = Pdf::loadView('reportes.historial_pdf', $data)
                  ->setPaper('a4', 'landscape'); // horizontal por ancho de columnas

        return $pdf->download("historial_{$noControl}.pdf");
        // o: return $pdf->stream(...);
    }

    public function ver(Request $request)
    {
        $request->validate([
            'no_control' => ['required', 'string'],
        ]);

        $noControl = $request->no_control;

        // Llamamos al SP
        $historial = DB::select('CALL historial_alumno(?)', [$noControl]);

        // Para encabezado
        $alumnoInfo = DB::table('alumnos')
            ->select('no_control', DB::raw("TRIM(CONCAT(nombre,' ',apellido_pat,' ',COALESCE(apellido_mat,''))) AS nombre_completo"))
            ->where('no_control', $noControl)
            ->first();

        return view('reportes.alumno-ver', compact('alumnoInfo', 'historial'));
    }
}
