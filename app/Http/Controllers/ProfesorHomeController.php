<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Inscripcione;
use App\Models\Alumno;
use App\Models\Profesore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactosProfesore;

class ProfesorHomeController extends Controller
{
    protected function getProfesor(): ?Profesore
    {
        $user = Auth::user();
        $profesorId = $user->profesor_id ?? null;

        if (!$profesorId) {
            return null;
        }

        return Profesore::find($profesorId);
    }

    // HOME DEL PROFE
    public function index()
    {
        $profesor = $this->getProfesor();

        // Cursos vigentes (puedes ajustar la condición de "vigente")
        $cursosVigentes = Curso::with(['materia', 'periodo'])
            ->where('fk_profesor', $profesor?->id_profesor)
            ->orderBy('id_curso', 'ASC')
            ->get();

        // Contadores rápidos para el dashboard
        $totalCursos = $cursosVigentes->count();

        return view('auth.homeprofe', compact('profesor', 'cursosVigentes', 'totalCursos'));
    }

    // LISTA DE CURSOS VIGENTES
    public function cursosVigentes()
    {
        $profesor = $this->getProfesor();

        $cursos = Curso::with(['materia', 'periodo'])
            ->where('fk_profesor', $profesor?->id_profesor)
            ->orderBy('id_curso')
            ->get();

        return view('profesore.cursos_vigentes', compact('profesor', 'cursos'));
    }

    // HISTORIAL DE CURSOS (periodos anteriores)
    public function cursosHistorial()
    {
        $profesor = $this->getProfesor();

        $cursos = Curso::with(['materia', 'periodo'])
            ->where('fk_profesor', $profesor?->id_profesor)
            ->orderBy('id_curso', 'DESC')
            ->get();

        return view('profesore.cursos_historial', compact('profesor', 'cursos'));
    }

    // LISTA DE ALUMNOS DE UN CURSO
    public function listaAlumnos($cursoId)
    {
        $profesor = $this->getProfesor();

        $curso = Curso::with(['materia', 'periodo'])
            ->where('fk_profesor', $profesor?->id_profesor)
            ->findOrFail($cursoId);

        $inscripciones = Inscripcione::with('alumno')
            ->where('curso_id', $curso->id_curso)
            ->orderBy('alumno_no_control')
            ->get();

        return view('profesore.lista_alumnos', compact('profesor', 'curso', 'inscripciones'));
    }

    // FORMULARIO PARA CAPTURAR / EDITAR CALIFICACIONES
    public function formCalificaciones($cursoId)
    {
        $profesor = $this->getProfesor();

        $curso = Curso::with(['materia', 'periodo'])
            ->where('fk_profesor', $profesor?->id_profesor)
            ->findOrFail($cursoId);

        // Solo alumnos inscritos en este curso
        $inscripciones = Inscripcione::with('alumno')
            ->where('curso_id', $curso->id_curso)
            ->where('estado', 'Inscrito') // opcional
            ->orderBy('alumno_no_control')
            ->get();

        return view('profesore.calificaciones', compact('profesor', 'curso', 'inscripciones'));
    }

    // GUARDAR CALIFICACIONES
    public function guardarCalificaciones(Request $request, $cursoId)
    {
        $profesor = $this->getProfesor();

        $curso = Curso::where('fk_profesor', $profesor?->id_profesor)
            ->findOrFail($cursoId);

        $data = $request->validate([
            'calificaciones'   => 'array',
            'calificaciones.*' => 'nullable|numeric|min:0|max:100',
        ]);

        foreach ($data['calificaciones'] ?? [] as $inscripcionId => $valor) {
            $inscripcion = Inscripcione::where('curso_id', $curso->id_curso)
                ->where('id', $inscripcionId)
                ->first();

            if ($inscripcion) {
                $inscripcion->promedio = $valor !== null ? $valor : null;
                $inscripcion->save();
            }
        }

        return redirect()
            ->route('profesor.curso.calificaciones', $curso->id_curso)
            ->with('success', 'Calificaciones actualizadas correctamente.');
    }

    public function datosPersonales()
    {
        $user = Auth::user();

        // Relación profesor ↔ user: ajusta según tu estructura
        // Aquí asumo que user tiene profesor_id
        $profesor = Profesore::where('id_profesor', $user->profesor_id)->first();

        if (!$profesor) {
            abort(403, 'No se encontró la ficha de profesor. Pide al administrador que vincule tu usuario.');
        }

        // Contacto del profe (si existe)
        $contacto = ContactosProfesore::where('fk_profesor', $profesor->id_profesor)->first();

        return view('profesore.datos_personales', [
            'user'     => $user,
            'profesor' => $profesor,
            'contacto' => $contacto,
        ]);
    }

}
