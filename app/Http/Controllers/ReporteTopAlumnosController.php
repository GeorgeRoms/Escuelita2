<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteTopAlumnosController extends Controller
{
    public function ver(Request $request)
    {
        $request->validate([
            'carrera_id' => ['required','integer','min:1'],
        ]);
        $carreraId = (int) $request->carrera_id;

        $rows = DB::select('CALL top10_promedios_por_carrera(?)', [$carreraId]);

        // Etiqueta de la carrera para el encabezado
        $carrera = DB::table('carreras')->where('id_carrera',$carreraId)->value('nombre_carr');

        return view('reportes.top-alumnos-ver', compact('rows','carrera'));
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'carrera_id' => ['required','integer','min:1'],
        ]);
        $carreraId = (int) $request->carrera_id;

        $rows = DB::select('CALL top10_promedios_por_carrera(?)', [$carreraId]);
        $carrera = DB::table('carreras')->where('id_carrera',$carreraId)->value('nombre_carr');

        // Logo base64 (opcional, mismo patrón que ya usas)
        $logoB64 = null;
        $logoPath = public_path('images/escuelita-logo.png');
        if (is_file($logoPath)) {
            $mime = mime_content_type($logoPath) ?: 'image/png';
            $logoB64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoPath));
        }

        $pdf = Pdf::loadView('reportes.top-alumnos-pdf', [
            'rows'    => $rows,
            'carrera' => $carrera,
            'logoB64' => $logoB64,
            'fecha'   => now()->format('d/m/Y H:i'),
        ])->setPaper('a4','portrait');

        return $pdf->download("top10_promedios_{$carreraId}.pdf");
    }
}
