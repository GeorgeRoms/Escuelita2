<?php

namespace App\Http\Controllers;

use App\Models\Inscripcione;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\InscripcioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;
use App\Models\Curso;
use Illuminate\Database\QueryException;
use App\Support\Safe;
use App\Support\Responder;

class InscripcioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $inscripciones = Inscripcione::paginate();
                return [$inscripciones, $request];
            },
            function ($payload) {
                [$inscripciones, $request] = $payload;

                return view('inscripcione.index', compact('inscripciones'))
                    ->with('i', ($request->input('page', 1) - 1) * $inscripciones->perPage());
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Safe::run(
            function () {
                $inscripcione = new Inscripcione();
                [$alumnos, $cursos] = $this->catalogosInscripciones();
                return compact('inscripcione', 'alumnos', 'cursos');
            },
            function ($data) {
                return view('inscripcione.create', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo cargar el formulario. Folio: '.$folio);
            }
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InscripcioneRequest $request)
    {
        $data = $request->validated();

        return Safe::run(
            function () use ($data) {
                return DB::transaction(function () use ($data) {
                    // Evita duplicar la misma inscripción (alumno + curso)
                    $ins = Inscripcione::firstOrCreate(
                        [
                            'alumno_no_control' => $data['alumno_no_control'],
                            'curso_id'          => $data['curso_id'],
                        ],
                        [
                            'estado'   => $data['estado'],
                            'intento'  => $data['intento'],
                            'semestre' => $data['semestre'] ?? null,
                        ]
                    );

                    if (!$ins->wasRecentlyCreated) {
                        // ya existía, lanzamos excepción para capturar en onError
                        throw new \RuntimeException('El alumno ya está inscrito en ese curso.');
                    }

                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'inscripciones.index', 'Inscripción registrada.', null, 201);
            },
            function ($folio, $e) use ($request) {
                // Si fue duplicado
                if ($e instanceof \RuntimeException && str_contains($e->getMessage(), 'inscrito')) {
                    return back()->withErrors('El alumno ya está inscrito en ese curso.')->withInput();
                }

                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Safe::run(
            function () use ($id) {
                $inscripcione = Inscripcione::find($id);
                return compact('inscripcione');
            },
            function ($data) {
                return view('inscripcione.show', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo mostrar la inscripción. Folio: '.$folio);
            }
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inscripcione $inscripcione)
    {
        return Safe::run(
            function () use ($inscripcione) {
                [$alumnos, $cursos] = $this->catalogosInscripciones();
                return compact('inscripcione', 'alumnos', 'cursos');
            },
            function ($data) {
                return view('inscripcione.edit', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo cargar la edición. Folio: '.$folio);
            }
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InscripcioneRequest $request, Inscripcione $inscripcione)
    {
        $data = $request->validated();

        return Safe::run(
            function () use ($inscripcione, $data) {
                return DB::transaction(function () use ($inscripcione, $data) {
                    // Proteger duplicado al editar (alumno+curso únicos)
                    $exist = Inscripcione::where('alumno_no_control', $data['alumno_no_control'])
                        ->where('curso_id', $data['curso_id'])
                        ->where('id', '<>', $inscripcione->id)
                        ->exists();

                    if ($exist) {
                        throw new \RuntimeException('Ya existe una inscripción con ese alumno y curso.');
                    }

                    $inscripcione->update($data);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'inscripciones.index', 'Inscripción actualizada.');
            },
            function ($folio, $e) use ($request) {
                if ($e instanceof \RuntimeException && str_contains($e->getMessage(), 'inscripción')) {
                    return back()->withErrors('Ya existe una inscripción con ese alumno y curso.')->withInput();
                }

                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy(Inscripcione $inscripcione)
    {
        return Safe::run(
            function () use ($inscripcione) {
                return DB::transaction(function () use ($inscripcione) {
                    $inscripcione->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('inscripciones.index')
                    ->with('success', 'Inscripción eliminada.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar la inscripción. Folio: '.$folio);
            }
        );
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

