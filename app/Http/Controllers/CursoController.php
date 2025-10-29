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

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $cursos = Curso::with([
                        'materia',
                        'profesor',
                        'aula.edificio',   // aula + edificio (join implícito por relación)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Safe::run(
            function () {
                $curso = new \App\Models\Curso();

                // Materias
                $materias = Materia::orderBy('nombre_mat')
                    ->pluck('nombre_mat', 'id_materia');

                // Profesores (nombre completo)
                $profesores = Profesore::query()
                    ->orderBy('apellido_pat')
                    ->orderBy('apellido_mat')
                    ->orderBy('nombre')
                    ->get()
                    ->mapWithKeys(function ($p) {
                        $nombre = trim($p->apellido_pat.' '.$p->apellido_mat.', '.$p->nombre);
                        return [$p->id_profesor => $nombre];
                    });

                // Aulas: "A - 101" (join edificios)
                $aulas = \App\Models\Aula::join('edificios','edificios.id','=','aulas.edificio_id')
                    ->orderBy('edificios.codigo')
                    ->orderBy('aulas.salon')
                    ->get([
                        'aulas.id',
                        DB::raw("CONCAT(edificios.codigo,' - ',aulas.salon) AS label")
                    ])
                    ->pluck('label','id'); // [id => "A - 101"]

                // Periodos (si los usas en el form)
                $periodos = Periodo::anioActual()
                    ->orderBy('nombre')
                    ->get()
                    ->mapWithKeys(fn ($p) => [$p->id => "{$p->anio} {$p->nombre}"]);

                return compact('curso','materias','profesores','aulas','periodos');
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(CursoRequest $request)
    {
        $data = $request->validated();

        return Safe::run(
            function () use ($data) {
                return DB::transaction(function () use ($data) {
                    return Curso::create($data);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'cursos.index', 'Curso creado correctamente.', null, 201);
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Curso $curso)
    {
        return Safe::run(
            function () use ($curso) {
                $curso->load(['materia','profesor','aula.edificio','periodo']); // agrega 'carrera' si la tienes
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Curso $curso)
    {
        return Safe::run(
            function () use ($curso) {
                // Materias
                $materias = \App\Models\Materia::orderBy('nombre_mat')
                    ->pluck('nombre_mat', 'id_materia');

                // Profesores: nombre completo
                $profesores = \App\Models\Profesore::query()
                    ->orderBy('apellido_pat')->orderBy('apellido_mat')->orderBy('nombre')
                    ->get()
                    ->mapWithKeys(function ($p) {
                        $nombre = trim($p->apellido_pat.' '.$p->apellido_mat.', '.$p->nombre);
                        return [$p->id_profesor => $nombre];
                    });

                // Aulas: "CODIGO - SALON" (e.g., "A - 101")
                $aulas = \App\Models\Aula::join('edificios','edificios.id','=','aulas.edificio_id')
                    ->orderBy('edificios.codigo')->orderBy('aulas.salon')
                    ->get([
                        'aulas.id',
                        DB::raw("CONCAT(edificios.codigo,' - ',aulas.salon) AS label")
                    ])
                    ->pluck('label','id'); // [id => "A - 101"]

                // Periodos (si los usas en el form)
                $periodos = \App\Models\Periodo::orderBy('anio','desc')->orderBy('nombre')
                    ->get()
                    ->mapWithKeys(fn ($p) => [$p->id => "{$p->anio} {$p->nombre}"]);

                return compact('curso','materias','profesores','aulas','periodos');
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

    /**
     * Update the specified resource in storage.
     */
    public function update(CursoRequest $request, Curso $curso)
    {
        $data = $request->validated();

        return Safe::run(
            function () use ($curso, $data) {
                return DB::transaction(function () use ($curso, $data) {
                    $curso->fill($data)->save();
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'cursos.index', 'Curso actualizado.');
            },
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
                    // (opcional) marca las inscripciones como "Baja/Cancelado"
                    DB::table('inscripciones')
                      ->where('curso_id', $curso->id_curso)
                      ->update(['estado' => 'Baja', 'updated_at' => now()]);

                    // Soft delete del curso (no rompe FKs)
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

