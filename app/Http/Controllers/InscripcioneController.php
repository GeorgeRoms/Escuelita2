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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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

    $solo = Arr::only($data, [
        'alumno_no_control',
        'curso_id',
        'promedio', // nuevo
    ]);

    return Safe::run(
        function () use ($solo) {
            return DB::transaction(function () use ($solo) {
                // Calcular intento por materia (no por curso)
                $intento = $this->calcularIntento(
                    $solo['alumno_no_control'],
                    (int)$solo['curso_id']
                );

                // Si ya está aprobada, NO dejamos inscribir
                if ($intento === 'APROBADA') {
                    throw new \RuntimeException('aprobada'); // la capturamos abajo
                    }

                // Promedio por defecto = 100 si no lo enviaron o viene vacío
                $promedio = (array_key_exists('promedio', $solo) && $solo['promedio'] !== null && $solo['promedio'] !== '')
                    ? $solo['promedio']
                    : 100;

                // Unicidad por (alumno_no_control, curso_id)
                $ins = Inscripcione::firstOrCreate(
                    [
                        'alumno_no_control' => $solo['alumno_no_control'],
                        'curso_id'          => $solo['curso_id'],
                    ],
                    [
                        'intento'  => $intento,
                        'promedio' => $promedio,
                    ]
                );

                if (!$ins->wasRecentlyCreated) {
                    throw new \RuntimeException('El alumno ya está inscrito en ese curso.');
                }
                return true;
            });
        },
        function () use ($request) {
            return Responder::ok($request, 'inscripciones.index', 'Inscripción registrada.', null, 201);
        },
        function ($folio, $e) use ($request) {
            if ($e instanceof \RuntimeException && str_contains($e->getMessage(), 'inscrito')) {
                return back()->withErrors('El alumno ya está inscrito en ese curso.')->withInput();
            }

            if (str_contains($e->getMessage(), 'aprobada')) {
            return back()
                ->withErrors(['intento' => 'El alumno ya aprobó la materia y no puede inscribirse de nuevo.'])
                ->withInput();
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

    $solo = Arr::only($data, [
        'alumno_no_control',
        'curso_id',
        'promedio',
    ]);

    return Safe::run(
        function () use ($inscripcione, $solo) {
            return DB::transaction(function () use ($inscripcione, $solo) {
                
                // Valores efectivos (si no vienen en el form, usa los actuales)
                $alumnoNoControl = $solo['alumno_no_control'] ?? $inscripcione->alumno_no_control;
                $cursoId         = isset($solo['curso_id']) ? (int)$solo['curso_id'] : (int)$inscripcione->curso_id;

                // Unicidad con valores efectivos
                $exist = Inscripcione::where('alumno_no_control', $alumnoNoControl)
                    ->where('curso_id', $cursoId)
                    ->where('id', '<>', $inscripcione->id)
                    ->exists();

                if ($exist) {
                    throw new \RuntimeException('Ya existe una inscripción con ese alumno y curso.');
                }

                // Promedio: si no viene, mantener el actual (o 100 si no hubiera)
                $promedio = (array_key_exists('promedio', $solo) && $solo['promedio'] !== null && $solo['promedio'] !== '')
                    ? $solo['promedio']
                    : ($inscripcione->promedio ?? 100);

                // Armar payload final sin cambiar tus nombres
                $payload = array_merge($solo, [
                    'alumno_no_control' => $alumnoNoControl,
                    'curso_id'          => $cursoId,
                    'promedio'          => $promedio,
                ]);

                $inscripcione->update($payload);
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


    public function intento(Request $request)
{
    $alumno = $request->query('alumno_no_control');
    $curso  = (int) $request->query('curso_id');

    if (!$alumno || !$curso) {
        return response()->json(['ok' => true, 'intento' => '—'], 200);
    }

    try {
        $intento = $this->calcularIntento($alumno, $curso);
        return response()->json(['ok' => true, 'intento' => $intento], 200);
    } catch (\Throwable $e) {
        // Aquí ya no devolvemos detalle del error al cliente
        return response()->json(['ok' => false, 'intento' => '—'], 200);
    }
}


    private function calcularIntento(string $noControl, int $cursoId): string
{
    // Trae solo lo necesario y usa fk_materia
    $curso     = Curso::select('id_curso','fk_materia')->findOrFail($cursoId);
    $materiaId = $curso->fk_materia;

    if (empty($materiaId)) {
        // Si el curso no tiene materia asociada, asumimos primera vez
        return 'Normal';
    }

    // 1) ¿Ya tiene la MATERIA APROBADA? (cualquier curso de esa materia)
    $yaAprobada = Inscripcione::query()
        ->join('cursos', 'cursos.id_curso', '=', 'inscripciones.curso_id')
        ->where('inscripciones.alumno_no_control', $noControl)
        ->where('cursos.fk_materia', $materiaId)
        ->where('inscripciones.promedio', '>=', 70)
        ->exists();

    if ($yaAprobada) {
        // OJO: esto NO se guarda en la BD (enum),
        // solo se usa para el preview y para bloquear el store()
        return 'APROBADA';
    }

    // 2) Si no está aprobada, calculamos el siguiente intento Normal/Repite/Especial
    $maxPrev = Inscripcione::query()
        ->join('cursos', 'cursos.id_curso', '=', 'inscripciones.curso_id')
        ->where('inscripciones.alumno_no_control', $noControl)
        ->where('cursos.fk_materia', $materiaId)
        ->max(DB::raw("
            CASE inscripciones.intento
                WHEN 'Especial' THEN 3
                WHEN 'Repite'  THEN 2
                WHEN 'Normal'  THEN 1
                ELSE 0
            END
        "));

    $maxPrev = (int)($maxPrev ?? 0);
    $next    = min(3, $maxPrev + 1);

    return $next === 1 ? 'Normal'
         : ($next === 2 ? 'Repite'
         : 'Especial');
}


}

