<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteCarreraPeriodoController extends Controller
{
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

        return view('reportes.carrera-periodo-ver', compact(
            'carrera','periodoEtiqueta','kpis','porMateria','porProfesor','porAlumno'
        ));
    }
}