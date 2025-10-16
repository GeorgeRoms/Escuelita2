<?php

// app/Http/Controllers/ReporteEspecialController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteEspecialController extends Controller
{
    public function resumenPorCarrera(Request $request)
    {
        $carrera = $request->input('carrera');

        $pdo = DB::getPdo();
        $stmt = $pdo->prepare("CALL resumen_especial_por_carrera(:carrera)");
        $stmt->bindParam(':carrera', $carrera);
        $stmt->execute();

        $rs1 = $stmt->fetchAll(\PDO::FETCH_ASSOC);   // total alumnos
        $stmt->nextRowset();
        $rs2 = $stmt->fetchAll(\PDO::FETCH_ASSOC);   // materias detalle
        $stmt->nextRowset();
        $rs3 = $stmt->fetchAll(\PDO::FETCH_ASSOC);   // total materias
        $stmt->nextRowset();
        $rs4 = $stmt->fetchAll(\PDO::FETCH_ASSOC);   // *** detalle por alumno ***
        $stmt->closeCursor();

        return view('reportes.especial', [
            'carrera'         => $carrera,
            'totAlumnos'      => $rs1[0]['total_alumnos_especial'] ?? 0,
            'materiasDetalle' => $rs2,
            'totMaterias'     => $rs3[0]['total_materias_en_especial'] ?? 0,
            'alumnosDetalle'  => $rs4, // <-- nuevo
        ]);
    }
}

