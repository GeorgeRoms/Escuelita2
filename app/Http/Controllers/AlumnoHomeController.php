<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Alumno;
use App\Models\alumno_carrera;
use App\Models\Inscripcione;
use App\Models\Curso;
use App\Models\Materia;

class AlumnoHomeController extends Controller
{
    public function index()
{
    $user = Auth::user();
    $noControl = $user->alumno_no_control ?? $user->no_control ?? null;

    // Buscar ficha de alumno (si existe)
    $alumnoModel = null;
    if ($noControl) {
        $alumnoModel = Alumno::with('carreras')->where('no_control', $noControl)->first();
    }

    // Para el saludo y datos generales
    $alumnoParaVista = $alumnoModel ?: $user;

    // ===== ESTATUS (desde alumno_carrera) =====
    $estatus = 'N/A';
    if ($alumnoModel && $alumnoModel->carreras instanceof \Illuminate\Support\Collection && $alumnoModel->carreras->isNotEmpty()) {
        // Tomamos la primera carrera activa y leemos el pivot->estatus
        $estatusPivot = $alumnoModel->carreras->first()->pivot->estatus ?? null;
        if ($estatusPivot) {
            $estatus = $estatusPivot;
        }
    }

    // ===== PROMEDIO (desde inscripciones) =====
    $promedio = null;
    if ($noControl) {
        $promedio = Inscripcione::where('alumno_no_control', $noControl)
            ->whereNotNull('promedio')
            ->avg('promedio');
    }

    // Pasamos materias de ejemplo y los nuevos datos calculados
    $materias = Materia::all();

    return view('homealumn', [
        'alumno'   => $alumnoParaVista,
        'materias' => $materias,
        'estatus'  => $estatus,
        'promedio' => $promedio,
    ]);
}


    public function kardex()
{
    $user = Auth::user();
    $noControl = $user->alumno_no_control ?? $user->no_control ?? null;

    $alumno = null;
    if ($noControl) {
        $alumno = Alumno::where('no_control', $noControl)->first();
    }

    // Base: todas las inscripciones del alumno con relaciones necesarias
    $baseQuery = Inscripcione::with([
            'curso.materia',
            'curso.profesor',
            'curso.periodo',
        ])
        ->where('alumno_no_control', $noControl);

    // 1) Materias actualmente cursando -> Inscrito y SIN promedio
    $cursando = (clone $baseQuery)
        ->where('estado', 'Inscrito')
        ->whereNull('promedio')
        ->get();

    // 2) Materias cursadas anteriormente -> con promedio capturado
    $cursadas = (clone $baseQuery)
        ->whereNotNull('promedio')
        ->get();

    // 3) Materias por cursarse:
    //    tomamos todas las materias y excluimos las que ya aparecen
    //    en alguna inscripción del alumno (cursando o cursadas).
    $idsMateriasCursadasOCursando = $baseQuery
        ->get()
        ->pluck('curso.materia.id_materia')  // gracias al with('curso.materia')
        ->filter()
        ->unique()
        ->values();

    $porCursar = Materia::when($idsMateriasCursadasOCursando->isNotEmpty(), function ($q) use ($idsMateriasCursadasOCursando) {
        $q->whereNotIn('id_materia', $idsMateriasCursadasOCursando);
    })
    ->orderBy('id_materia')   // o 'nombre_mat', como prefieras
    ->get();


    return view('alumno.kardex', compact('alumno', 'cursando', 'cursadas', 'porCursar'));
}


    public function horarios()
{
    $user = Auth::user();
    $noControl = $user->alumno_no_control ?? $user->no_control ?? null;

    $alumno = null;
    if ($noControl) {
        $alumno = Alumno::where('no_control', $noControl)->first();
    }

    // Base: todas las inscripciones del alumno con sus relaciones
    $baseQuery = Inscripcione::with([
            'curso.materia',
            'curso.profesor',
            'curso.periodo',
        ])
        ->where('alumno_no_control', $noControl);

    // 1) Materias actualmente cursando  (estado = Inscrito)
    $cursando = (clone $baseQuery)
    ->where('estado', 'Inscrito')
    ->whereNull('promedio')
    ->get();

    // 2) Materias cursadas anteriormente (estado distinto de Inscrito)
    $cursadas = (clone $baseQuery)
    ->whereNotNull('promedio')
    ->get();

    return view('alumno.horarios', compact('alumno', 'cursando', 'cursadas'));
}


    public function calificaciones()
{
    $user = Auth::user();
    $noControl = $user->alumno_no_control ?? $user->no_control ?? null;

    $alumno = null;
    if ($noControl) {
        $alumno = Alumno::where('no_control', $noControl)->first();
    }

    $califs = Inscripcione::with('curso.materia')
        ->when($noControl, function ($q) use ($noControl) {
            $q->where('alumno_no_control', $noControl);
        })
        ->get();

    return view('alumno.calificaciones', compact('alumno', 'califs'));
}


    public function datos()
{
    $user = Auth::user();
    $noControl = $user->alumno_no_control ?? $user->no_control ?? null;

    if (!$noControl) {
        return redirect()
            ->route('alumno.home')
            ->with('error', 'No se pudo determinar tu número de control.');
    }

    $alumno = Alumno::with('carreras')
        ->where('no_control', $noControl)
        ->first();

    if (!$alumno) {
        return redirect()
            ->route('alumno.home')
            ->with('error', 'No se encontró tu ficha de alumno.');
    }

    $contacto = \App\Models\ContactosAlumno::where('fk_alumno', $noControl)->first();

    // Estatus
    $estatus = 'N/A';
    if ($alumno->carreras instanceof \Illuminate\Support\Collection && $alumno->carreras->isNotEmpty()) {
        $estatusPivot = $alumno->carreras->first()->pivot->estatus ?? null;
        if ($estatusPivot) {
            $estatus = $estatusPivot;
        }
    }

    $correo   = $contacto->correo   ?? $user->email ?? null;
    $telefono = $contacto->telefono ?? null;

    // 🏠 Dirección: usamos lo que exista
    $direccion = null;
    if ($contacto) {
        // Si tienes campos atomizados (calle, colonia, etc.)
        if (isset($contacto->calle) || isset($contacto->colonia)) {
            $partes = [];

            if (!empty($contacto->calle))    $partes[] = $contacto->calle;
            if (!empty($contacto->num_ext))  $partes[] = 'Ext. ' . $contacto->num_ext;
            if (!empty($contacto->num_int))  $partes[] = 'Int. ' . $contacto->num_int;
            if (!empty($contacto->colonia))  $partes[] = $contacto->colonia;
            if (!empty($contacto->cp))       $partes[] = 'CP ' . $contacto->cp;
            if (!empty($contacto->estado))   $partes[] = $contacto->estado;
            if (!empty($contacto->pais))     $partes[] = $contacto->pais;

            $direccion = implode(', ', $partes);
        } else {
            // Versión viejita con un solo campo "direccion"
            $direccion = $contacto->direccion ?? null;
        }
    }

    return view('alumno.datos', compact('alumno', 'correo', 'telefono', 'estatus', 'direccion'));
}




    public function cursosActivos()
{
    $user = Auth::user();
    $noControl = $user->alumno_no_control ?? $user->no_control ?? null;

    $alumno = null;
    if ($noControl) {
        $alumno = Alumno::where('no_control', $noControl)->first();
    }

    $cursos = Curso::with(['materia', 'aula.edificio', 'profesor'])
        ->join('inscripciones', 'inscripciones.curso_id', '=', 'cursos.id_curso')
        ->when($noControl, function ($q) use ($noControl) {
            $q->where('inscripciones.alumno_no_control', $noControl);
        })
        ->where('inscripciones.estado', 'Inscrito')
        ->select('cursos.*', 'inscripciones.estado as inscripcion_estado')
        ->get();

    return view('alumno.cursos_activos', compact('alumno', 'cursos'));
}




    public function planMaterias()
{
    $user = Auth::user();
    $noControl = $user->alumno_no_control ?? $user->no_control ?? null;

    $alumno = null;
    if ($noControl) {
        $alumno = Alumno::where('no_control', $noControl)->first();
    }

    $materias = Materia::orderBy('semestre')->get();

    return view('alumno.plan_materias', compact('alumno','materias'));
}

}

