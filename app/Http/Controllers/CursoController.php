<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Aula;
use App\Models\Profesor;
use App\Models\Periodo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CursoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Materia;
use App\Models\Profesore;
use App\Models\Edificio;
use Illuminate\Support\Facades\DB;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cursos = Curso::with([
                'materia',
                'profesor',
                'aula.edificio',   // aula + edificio (join implícito por relación)
                'periodo',
            ])
            ->orderBy('id_curso')
            ->paginate();

        return view('curso.index', compact('cursos'))
            ->with('i', ($request->input('page', 1) - 1) * $cursos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
                ])->pluck('label','id'); // [id => "A - 101"]
                
                // Periodos (si los usas en el form)
                $periodos = \App\Models\Periodo::orderBy('anio','desc')->orderBy('nombre')
                ->get()
                ->mapWithKeys(fn ($p) => [$p->id => "{$p->anio} {$p->nombre}"]);

                return view('curso.create', compact('curso','materias','profesores','aulas','periodos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CursoRequest $request)
    {
        $data = $request->validated();
        Curso::create($data);

        return redirect()->route('cursos.index')->with('success','Curso creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Curso $curso)
    {
        $curso->load(['materia','profesor','aula.edificio','periodo']); // agrega 'carrera' si la tienes
        return view('curso.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Curso $curso)
{
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

    return view('curso.edit', compact('curso','materias','profesores','aulas','periodos'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(CursoRequest $request, Curso $curso)
    {
        $data = $request->validated();
        $curso->fill($data)->save();

        return redirect()->route('cursos.index')->with('success','Curso actualizado.');
    }

    public function destroy(\App\Models\Curso $curso)
{
    try {
        DB::transaction(function () use ($curso) {
            // (opcional) marca las inscripciones como "Baja/Cancelado"
            DB::table('inscripciones')
              ->where('curso_id', $curso->id_curso)
              ->update(['estado' => 'Baja', 'updated_at' => now()]);

            // Soft delete del curso (no rompe FKs)
            $curso->delete();
        });

        return redirect()->route('cursos.index')->with('success','Curso cancelado');
    } catch (\Throwable $e) {
        return redirect()->route('cursos.index')
            ->withErrors('No se pudo cancelar el curso: '.$e->getMessage());
    }
}
}
