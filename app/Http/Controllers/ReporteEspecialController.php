<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Carrera; // ⬅️ importa el modelo para el catálogo

class ReporteEspecialController extends Controller
{
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


