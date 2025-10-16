<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteProfesorController extends Controller
{
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
