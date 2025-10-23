<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteProfesorController extends Controller
{


    public function materiasProfesorPdf(Request $request)
{
    // Parámetros que ya usas para el reporte normal
    $profesorId = $request->query('profesor_id');     // ajusta si pasas nombre
    $periodoId  = $request->query('periodo_id');      // opcional

    // Llama al mismo SP/consulta que usas para $rows de la vista normal:
    // Ejemplo genérico (ajusta a tu implementación):
    $stmt = DB::getPdo()->prepare('CALL materias_impartidas_por_profesor(?, ?)'); // si tu SP acepta 2 params
    $stmt->execute([$profesorId, $periodoId]);
    $rows = $stmt->fetchAll(\PDO::FETCH_OBJ);
    $stmt->closeCursor();

    // Encabezados que ya muestras en el Blade normal
    $docente         = $rows[0]->docente  ?? '—';
    $periodoEtiqueta = $rows[0]->periodo  ?? ($request->query('periodo') ?? null);

    
    // Logo base64 (MIME correcto)
    $logoB64 = null;
    $logoPath = public_path('images/escuelita-logo.png');
    if (is_file($logoPath)) {
        $mime = mime_content_type($logoPath) ?: 'image/png';
        $logoB64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoPath));
    }
    
    
    $pdf = Pdf::loadView('reportes.profesor_pdf', [
        'docente'         => $docente,
        'periodoEtiqueta' => $periodoEtiqueta,
        'rows'            => $rows,
        'fecha'           => now()->format('d/m/Y H:i'),
        'logoB64'         => $logoB64,
    ])->setPaper('a4', 'landscape');

    return $pdf->download("materias_profesor_{$profesorId}.pdf");
    // o .stream(...) para verlo en el navegador
}

    public function ver(Request $request)
    {
        $request->validate([
            'profesor_id' => ['required','integer','min:1'],
            'periodo_id'  => ['nullable','integer','min:1'],
        ]);

        $profesorId = (int) $request->profesor_id;
        $periodoId  = $request->filled('periodo_id') ? (int) $request->periodo_id : null;

        // Llamada al SP (un solo result set)
        $rows = DB::select('CALL materias_impartidas_por_profesor(?, ?)', [$profesorId, $periodoId]);

        // Encabezado bonito (docente y periodo si se filtró)
        $docente = null;
        if (!empty($rows)) {
            $docente = $rows[0]->docente;
        } else {
            $q = DB::table('profesores')
                ->select(DB::raw("TRIM(CONCAT(nombre,' ',apellido_pat,' ',COALESCE(apellido_mat,''))) AS docente"))
                ->where('id_profesor', $profesorId)->first();
            $docente = $q->docente ?? 'Profesor';
        }

        // Si seleccionó periodo, lo traemos para el subtítulo
        $periodoEtiqueta = null;
        if ($periodoId) {
            $p = DB::table('periodos')->selectRaw("CONCAT(nombre,' ',anio) AS etiqueta")->where('id',$periodoId)->first();
            $periodoEtiqueta = $p->etiqueta ?? null;
        }

        return view('reportes.profesor-ver', [
            'docente'         => $docente,
            'periodoEtiqueta' => $periodoEtiqueta,
            'rows'            => $rows,
        ]);
    }
}
