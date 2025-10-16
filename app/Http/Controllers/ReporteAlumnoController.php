<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteAlumnoController extends Controller
{
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
