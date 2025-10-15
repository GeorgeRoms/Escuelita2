<?php

namespace App\Http\Controllers;

use App\Models\Inscripcione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\InscripcioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Alumno;
use App\Models\Curso;
use Illuminate\Database\QueryException;

class InscripcioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $inscripciones = Inscripcione::paginate();

        return view('inscripcione.index', compact('inscripciones'))
            ->with('i', ($request->input('page', 1) - 1) * $inscripciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inscripcione = new Inscripcione();

        [$alumnos, $cursos] = $this->catalogosInscripciones();

        // importante: pasar ambos a la vista
        return view('inscripcione.create', compact('inscripcione','alumnos','cursos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InscripcioneRequest $request)
    {
        $data = $request->validated();

        // ✅ Evita duplicar la misma inscripción (alumno + curso)
        $ins = Inscripcione::firstOrCreate(
            ['alumno_no_control' => $data['alumno_no_control'], 'curso_id' => $data['curso_id']],
            [
                'estado'              => $data['estado'],
                'intento'             => $data['intento'],
                'semestre'            => $data['semestre'] ?? null,
            ]
        );

        // Si ya existía, puedes decidir actualizar o avisar:
        if (!$ins->wasRecentlyCreated) {
            return redirect()->route('inscripciones.index')
                ->withErrors('El alumno ya está inscrito en ese curso.');
        }

        return redirect()->route('inscripciones.index')->with('success','Inscripción registrada.');
    }

        /**
        * Display the specified resource.
     */
    public function show($id): View
    {
        $inscripcione = Inscripcione::find($id);

        return view('inscripcione.show', compact('inscripcione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inscripcione $inscripcione)
    {
        [$alumnos, $cursos] = $this->catalogosInscripciones();

        return view('inscripcione.edit', compact('inscripcione','alumnos','cursos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InscripcioneRequest $request, Inscripcione $inscripcione)
    {
        $data = $request->validated();

        // Proteger duplicado al editar (alumno+curso únicos)
        $exist = Inscripcione::where('alumno_no_control',$data['alumno_no_control'])
            ->where('curso_id',$data['curso_id'])
            ->where('id','<>',$inscripcione->id)
            ->exists();

        if ($exist) {
            return back()->withErrors('Ya existe una inscripción con ese alumno y curso.')->withInput();
        }

        $inscripcione->update($data);

        return redirect()->route('inscripciones.index')->with('success','Inscripción actualizada.');
    }

    public function destroy(Inscripcione $inscripcione)
    {
        $inscripcione->delete();
        return redirect()->route('inscripciones.index')->with('success','Inscripción eliminada.');
    }

    private function catalogosInscripciones(): array
{
    // Alumnos: NOCTRL — Nombre Apellidos
    $alumnos = Alumno::orderBy('apellido_pat')
        ->orderBy('apellido_mat')
        ->orderBy('nombre')
        ->get()
        ->mapWithKeys(fn($a) => [
            $a->no_control => $a->no_control.' — '.$a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? '')
        ]);

    // Cursos: id — Materia (Profe) [Edif - Aula] {Año Periodo}
    $cursos = Curso::with(['materia','profesor','aula.edificio','periodo'])
        ->orderBy('id_curso')
        ->get()
        ->mapWithKeys(function($c){
            $ma = $c->materia?->nombre_mat;
            $pr = trim(($c->profesor->nombre ?? '').' '.($c->profesor->apellido_pat ?? ''));
            $ed = $c->aula?->edificio?->codigo;
            $au = $c->aula?->salon;
            $pe = $c->periodo ? ($c->periodo->anio.' '.$c->periodo->nombre) : null;

            $label = "{$c->id_curso} — {$ma}"
                     .($pr ? " ({$pr})" : '')
                     .($ed && $au ? " [{$ed} - {$au}]" : '')
                     .($pe ? " {{$pe}}" : '');

            return [$c->id_curso => $label];
        });

    return [$alumnos, $cursos];
}
}
