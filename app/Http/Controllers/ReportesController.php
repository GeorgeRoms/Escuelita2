<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function index()
    {
        $carreras = DB::table('carreras')->orderBy('nombre_carr')->pluck('nombre_carr')->toArray();

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

        // NUEVO: profesores
        $profesores = DB::table('profesores')
            ->select('id_profesor',
                     DB::raw("TRIM(CONCAT(nombre,' ',apellido_pat,' ',COALESCE(apellido_mat,''))) AS docente"))
            ->orderBy('apellido_pat')->orderBy('apellido_mat')->orderBy('nombre')
            ->get();

        // NUEVO: periodos
        $periodos = DB::table('periodos')
            ->select('id', DB::raw("CONCAT(nombre,' ',anio) AS etiqueta"))
            ->orderBy('anio','desc')->orderBy('nombre')->get();

        $alumnos = DB::table('alumnos')
        ->select('no_control',
                 DB::raw("TRIM(CONCAT(nombre,' ',apellido_pat,' ',COALESCE(apellido_mat,''))) AS nombre_completo"))
        ->orderBy('apellido_pat')
        ->orderBy('apellido_mat')
        ->orderBy('nombre')
        ->get();

        return view('reportes.index', compact('carreras','cursos','profesores','periodos','alumnos'));
    }
}