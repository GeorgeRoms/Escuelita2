<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
// use App\Models\Area; // Descomentar si usas Area::select() en lugar de DB::table('areas')

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

        // Obtener la lista de profesores para el desplegable (ahora se filtrará por AJAX)
        $profesores = DB::table('profesores')
            ->select('id_profesor',
                     DB::raw("TRIM(CONCAT(nombre,' ',apellido_pat,' ',COALESCE(apellido_mat,''))) AS docente"))
            ->orderBy('apellido_pat')->orderBy('apellido_mat')->orderBy('nombre')
            ->get();

        // Obtener la lista de periodos
        $periodos = DB::table('periodos')
            ->select('id', DB::raw("CONCAT(nombre,' ',anio) AS etiqueta"))
            ->orderBy('anio','desc')->orderBy('nombre')->get();

        // CONSULTA CORREGIDA FINAL (Se intenta 'nombre_area'): Obtener la lista de áreas.
        // Si esta falla, DEBES revisar tu tabla 'areas' y sustituir 'nombre_area' por el nombre REAL de la columna del nombre.
        $areas = DB::table('areas')
            // Se asume la clave primaria es 'id_area' y la columna de nombre es 'nombre_area'
            ->select('id_area as id', 'nombre_area as nombre') 
            ->orderBy('nombre_area') // Ordenar por la columna real
            ->get();
            
        $alumnos = DB::table('alumnos')
        ->select('no_control',
            DB::raw("TRIM(CONCAT(nombre,' ',apellido_pat,' ',COALESCE(apellido_mat,''))) AS nombre_completo"))
        ->orderBy('apellido_pat')
        ->orderBy('apellido_mat')
        ->orderBy('nombre')
        ->get();

        // Añadir 'areas' al array de variables pasadas a la vista
        return view('reportes.index', compact('carreras','cursos','profesores','periodos','alumnos', 'areas'));
    }

    // Nota: El método getProfesoresPorArea debe estar en ProfesorController, como confirmamos antes.
}
