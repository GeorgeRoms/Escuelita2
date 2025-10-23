<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteCursoController extends Controller
{

    public function cursoPdf(Request $request)
{
    $cursoId = $request->query('curso_id');

    // Llamamos al procedimiento almacenado
    $stmt = DB::getPdo()->prepare('CALL alumnos_por_curso(?)');
    $stmt->execute([$cursoId]);

    $alumnos = $stmt->fetchAll(\PDO::FETCH_OBJ);
    $stmt->closeCursor();

    // Opcional: información del curso (puedes ajustarlo a tu modelo)
    $cursoInfo = collect($alumnos)->first();

    // Logo base64 (MIME correcto)
    $logoB64 = null;
    $logoPath = public_path('images/escuelita-logo.png');
    if (is_file($logoPath)) {
        $mime = mime_content_type($logoPath) ?: 'image/png';
        $logoB64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($logoPath));
    }

    $data = [
        'cursoInfo' => $cursoInfo,
        'alumnos'   => $alumnos,
        'fecha'     => now()->format('d/m/Y H:i'),
        'logoB64'         => $logoB64,
    ];

    $pdf = Pdf::loadView('reportes.curso_pdf', $data)
              ->setPaper('a4', 'landscape'); // horizontal por si hay muchas columnas

    return $pdf->download("alumnos_curso_{$cursoId}.pdf");

    // Si prefieres verlo antes de descargar:
    // return $pdf->stream("alumnos_curso_{$cursoId}.pdf");
}
    
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
