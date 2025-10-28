<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Aula;
use App\Models\Profesor;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CursoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Models\Materia;
use App\Models\Profesore;
use App\Models\Edificio;
use App\Support\Safe;
use App\Support\Responder;
use Illuminate\Database\QueryException;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $cursos = Curso::with([
                        'materia',
                        'profesor',
                        'aula.edificio',
                        'periodo',
                    ])
                    ->orderBy('id_curso')
                    ->paginate();

                return [$cursos, $request];
            },
            function ($payload) {
                [$cursos, $request] = $payload;

                return view('curso.index', compact('cursos'))
                    ->with('i', ($request->input('page', 1) - 1) * $cursos->perPage());
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function create()
    {
        return Safe::run(
            function () {
                $curso = new \App\Models\Curso();

                $materias = Materia::orderBy('nombre_mat')
                    ->pluck('nombre_mat', 'id_materia');

                $profesores = Profesore::query()
                    ->orderBy('apellido_pat')
                    ->orderBy('apellido_mat')
                    ->orderBy('nombre')
                    ->get()
                    ->mapWithKeys(function ($p) {
                        $nombre = trim($p->apellido_pat.' '.$p->apellido_mat.', '.$p->nombre);
                        return [$p->id_profesor => $nombre];
                    });

                $aulas = \App\Models\Aula::join('edificios','edificios.id','=','aulas.edificio_id')
                    ->orderBy('edificios.codigo')
                    ->orderBy('aulas.salon')
                    ->get([
                        'aulas.id',
                        DB::raw("CONCAT(edificios.codigo,' - ',aulas.salon) AS label")
                    ])
                    ->pluck('label','id');

                // Periodos: solo vigentes
$periodosQ = Periodo::query();
if (method_exists(Periodo::class, 'scopeVigentes')) {
    $periodosQ = Periodo::vigentes();
} elseif (method_exists(Periodo::class, 'scopeAnioActual')) {
    $periodosQ = Periodo::anioActual();
} else {
    $periodosQ->where('anio', now()->year);
}

$periodos = $periodosQ
    ->orderBy('anio', 'desc')->orderBy('nombre')
    ->get()
    ->mapWithKeys(fn ($p) => [$p->id => "{$p->anio} {$p->nombre}"]);


                // NUEVO: días para el select
                $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

                return compact('curso','materias','profesores','aulas','periodos','dias');
            },
            function ($data) {
                return view('curso.create', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo cargar el formulario. Folio: '.$folio);
            }
        );
    }

    public function store(CursoRequest $request)
{
    $data = $request->validated();

    return Safe::run(
        // Trabajo
        function () use ($data) {
            try {
                return DB::transaction(function () use ($data) {
                    Curso::create($data);
                    return ['ok' => true];
                });
            } catch (QueryException $e) {
                if (str_contains($e->getMessage(), 'SQLSTATE[45000]')) {
                    // Mensaje proveniente del SIGNAL del trigger
                    $msg = $e->errorInfo[2] ?? 'Conflicto de horario';
                    return ['ok' => false, 'horario' => $msg];
                }
                // Deja que Safe::run maneje otros errores
                throw $e;
            }
        },
        // Éxito
        function ($result) use ($request) {
            if (is_array($result) && ($result['ok'] ?? false) === false) {
                return back()
                    ->withErrors(['horario' => $result['horario']])
                    ->withInput();
            }
            return Responder::ok($request, 'cursos.index', 'Curso creado correctamente.', null, 201);
        },
        // Falla genérica (otros errores)
        function ($folio) use ($request) {
            return Responder::fail($request, $folio, 'error.general');
        }
    );
}


    public function show(\App\Models\Curso $curso)
    {
        return Safe::run(
            function () use ($curso) {
                $curso->load(['materia','profesor','aula.edificio','periodo']);
                return $curso;
            },
            function ($curso) {
                return view('curso.show', compact('curso'));
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo mostrar el registro. Folio: '.$folio);
            }
        );
    }

    public function edit(\App\Models\Curso $curso)
    {
        return Safe::run(
            function () use ($curso) {
                $materias = \App\Models\Materia::orderBy('nombre_mat')
                    ->pluck('nombre_mat', 'id_materia');

                $profesores = \App\Models\Profesore::query()
                    ->orderBy('apellido_pat')->orderBy('apellido_mat')->orderBy('nombre')
                    ->get()
                    ->mapWithKeys(function ($p) {
                        $nombre = trim($p->apellido_pat.' '.$p->apellido_mat.', '.$p->nombre);
                        return [$p->id_profesor => $nombre];
                    });

                $aulas = \App\Models\Aula::join('edificios','edificios.id','=','aulas.edificio_id')
                    ->orderBy('edificios.codigo')->orderBy('aulas.salon')
                    ->get([
                        'aulas.id',
                        DB::raw("CONCAT(edificios.codigo,' - ',aulas.salon) AS label")
                    ])
                    ->pluck('label','id');

                // Periodos: solo vigentes
$periodosQ = \App\Models\Periodo::query();
if (method_exists(\App\Models\Periodo::class, 'scopeVigentes')) {
    $periodosQ = \App\Models\Periodo::vigentes();
} elseif (method_exists(\App\Models\Periodo::class, 'scopeAnioActual')) {
    $periodosQ = \App\Models\Periodo::anioActual();
} else {
    $periodosQ->where('anio', now()->year);
}

$periodos = $periodosQ
    ->orderBy('anio', 'desc')->orderBy('nombre')
    ->get()
    ->mapWithKeys(fn ($p) => [$p->id => "{$p->anio} {$p->nombre}"]);


                // NUEVO: días
                $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

                return compact('curso','materias','profesores','aulas','periodos','dias');
            },
            function ($data) {
                return view('curso.edit', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo cargar la edición. Folio: '.$folio);
            }
        );
    }

    public function update(CursoRequest $request, Curso $curso)
{
    $data = $request->validated();

    return Safe::run(
        // Trabajo
        function () use ($curso, $data) {
            try {
                return DB::transaction(function () use ($curso, $data) {
                    $curso->fill($data)->save();
                    return ['ok' => true];
                });
            } catch (QueryException $e) {
                if (str_contains($e->getMessage(), 'SQLSTATE[45000]')) {
                    $msg = $e->errorInfo[2] ?? 'Conflicto de horario';
                    return ['ok' => false, 'horario' => $msg];
                }
                throw $e;
            }
        },
        // Éxito
        function ($result) use ($request) {
            if (is_array($result) && ($result['ok'] ?? false) === false) {
                return back()
                    ->withErrors(['horario' => $result['horario']])
                    ->withInput();
            }
            return Responder::ok($request, 'cursos.index', 'Curso actualizado.');
        },
        // Falla genérica
        function ($folio) use ($request) {
            return Responder::fail($request, $folio, 'error.general');
        }
    );
}


    public function destroy(\App\Models\Curso $curso)
    {
        return Safe::run(
            function () use ($curso) {
                return DB::transaction(function () use ($curso) {
                    DB::table('inscripciones')
                      ->where('curso_id', $curso->id_curso)
                      ->update(['estado' => 'Baja', 'updated_at' => now()]);

                    $curso->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('cursos.index')->with('success','Curso cancelado');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo cancelar el curso. Folio: '.$folio);
            }
        );
    }
}


