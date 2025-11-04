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
            'periodo_id' => ['nullable','integer','min:1'],
        ]);
        $carreraId = (int) $request->carrera_id;
        $periodoId = $request->filled('periodo_id') ? (int) $request->periodo_id : null;

        $rows = DB::select('CALL top10_promedios_por_carrera(?, ?)', [$carreraId, $periodoId]);

        // Etiquetas
        $carrera = DB::table('carreras')->where('id_carrera',$carreraId)->value('nombre_carr');
        $periodo = $periodoId 
            ? DB::table('periodos')->where('id',$periodoId)->selectRaw("CONCAT(nombre,' ',anio) AS etiqueta")->value('etiqueta')
            : 'Todos los periodos';

        return view('reportes.top-alumnos-ver', compact('rows','carrera','periodo'))
        ->with(['carrera_id'=>$carreraId,'periodo_id'=>$periodoId]);
    }

    public function pdf(Request $request)
    {
        $request->validate([
            'carrera_id' => ['required','integer','min:1'],
            'periodo_id' => ['nullable','integer','min:1'],
        ]);
        $carreraId = (int) $request->carrera_id;
        $periodoId = $request->filled('periodo_id') ? (int) $request->periodo_id : null;

        $rows = DB::select('CALL top10_promedios_por_carrera(?, ?)', [$carreraId, $periodoId]);

        $carrera = DB::table('carreras')->where('id_carrera',$carreraId)->value('nombre_carr');
        $periodo = $periodoId 
            ? DB::table('periodos')->where('id',$periodoId)->selectRaw("CONCAT(nombre,' ',anio) AS etiqueta")->value('etiqueta')
            : 'Todos los periodos';

        // Logo base64 (opcional, mismo patrón que ya usas)
        $logoB64 = null;
        $logoPath = public_path('images/escuelita-logo.png');
        if (is_file($logoPath)) {
            $mime = mime_content_type($logoPath) ?: 'image/png';
            $logoB64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoPath));
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.top-alumnos-pdf', [
            'rows'    => $rows,
            'carrera' => $carrera,
            'periodo' => $periodo,
            'logoB64' => $logoB64,
            'fecha'   => now()->format('d/m/Y H:i'),
        ])->setPaper('a4','portrait');

        $slugPeriodo = $periodoId ? str_replace(' ', '_', $periodo) : 'todos';
        return $pdf->download("top10_promedios_{$carreraId}_{$slugPeriodo}.pdf");
    }
}
