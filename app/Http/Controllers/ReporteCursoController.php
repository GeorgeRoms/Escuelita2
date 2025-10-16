<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteCursoController extends Controller
{
    public function index()
    {
        // Catálogo bonito: Curso #, Materia, Grupo, Periodo (Enero-Junio 2025), Profe
        $cursos = DB::table('cursos as c')
            ->leftJoin('materias as m', 'm.id_materia', '=', 'c.fk_materia')
            ->leftJoin('periodos as p', 'p.id', '=', 'c.periodo_id')
            ->leftJoin('profesores as pr', 'pr.id_profesor', '=', 'c.fk_profesor')
            ->selectRaw("c.id_curso,
                         CONCAT('#', c.id_curso, ' — ',
                                COALESCE(m.nombre_mat,'(Sin materia)'), ' — ',
                                COALESCE(CONCAT(p.nombre,' ',p.anio),'(Sin periodo)'), ' — ',
                                TRIM(CONCAT(pr.nombre,' ',pr.apellido_pat,' ',COALESCE(pr.apellido_mat,'')))
                         ) AS etiqueta")
            ->orderBy('c.id_curso')
            ->get();

        return view('reportes.curso-index', compact('cursos'));
    }

    public function ver(Request $request)
    {
        $request->validate([
            'curso_id' => ['required','integer','min:1'],
        ]);

        $cursoId = (int) $request->curso_id;

        // Llamamos el SP (regresa UN solo result set)
        // Nota: DB::select maneja directamente el array de filas.
        $alumnos = DB::select('CALL alumnos_por_curso(?)', [$cursoId]);

        // Info del curso para encabezado
        $cursoInfo = DB::table('cursos as c')
            ->leftJoin('materias as m', 'm.id_materia', '=', 'c.fk_materia')
            ->leftJoin('periodos as p', 'p.id', '=', 'c.periodo_id')
            ->leftJoin('profesores as pr', 'pr.id_profesor', '=', 'c.fk_profesor')
            ->where('c.id_curso', $cursoId)
            ->selectRaw("c.id_curso,
                         COALESCE(m.nombre_mat,'(Sin materia)') as materia,
                         COALESCE(CONCAT(p.nombre,' ',p.anio),'(Sin periodo)') as periodo,
                         TRIM(CONCAT(pr.nombre,' ',pr.apellido_pat,' ',COALESCE(pr.apellido_mat,''))) as profesor")
            ->first();

        return view('reportes.curso-ver', compact('cursoInfo','alumnos'));
    }
}
