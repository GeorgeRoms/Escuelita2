<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CursoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Materia;
use App\Models\Profesore;
use App\Models\Edificio;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $cursos = \App\Models\Curso::with(['materia','profesor','carrera']) // agrega 'edificio' si aplica
            ->orderBy('id_curso') // o el campo que uses
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

    $materias = Materia::orderBy('nombre_mat')
        ->pluck('nombre_mat', 'id_materia'); // [id => nombre]

    // nombre completo del profe
    $profesores = Profesore::query()
        ->orderBy('nombre')
        ->orderBy('apellido_pat')
        ->get()
        ->mapWithKeys(function ($p) {
            $nombre = trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
            return [$p->id_profesor => $nombre];
        });

    // Si tu tabla edificios NO tiene id_edificio, usa 'edificio' como clave:
    $edificios = Edificio::orderBy('edificio')->orderBy('salon')->get()
        ->mapWithKeys(function ($e) {
            $label = 'Edif '.$e->edificio.' — Salón '.$e->salon;
            // usa $e->id_edificio si lo tienes; si no, usa $e->edificio
            $value = property_exists($e, 'id_edificio') ? $e->id_edificio : $e->edificio;
            return [$value => $label];
        });

    return view('curso.create', compact('curso','materias','profesores','edificios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CursoRequest $request): RedirectResponse
    {
        Curso::create($request->validated());

        return Redirect::route('cursos.index')
            ->with('success', 'Curso created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Curso $curso)
    {
        $curso->load(['materia','profesor','edificio']); // agrega 'carrera' si la tienes
        return view('curso.show', compact('curso'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Curso $curso)
    {
        // mismas listas que en create()
    $materias = Materia::orderBy('nombre_mat')->pluck('nombre_mat', 'id_materia');

    $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
        ->mapWithKeys(function ($p) {
            $nombre = trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
            return [$p->id_profesor => $nombre];
        });

    $edificios = Edificio::orderBy('edificio')->orderBy('salon')->get()
        ->mapWithKeys(function ($e) {
            $label = 'Edif '.$e->edificio.' — Salón '.$e->salon;
            $value = property_exists($e, 'id_edificio') ? $e->id_edificio : $e->edificio;
            return [$value => $label];
        });

    return view('curso.edit', compact('curso','materias','profesores','edificios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CursoRequest $request, Curso $curso): RedirectResponse
    {
        $curso->update($request->validated());

        return Redirect::route('cursos.index')
            ->with('success', 'Curso updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Curso::find($id)->delete();

        return Redirect::route('cursos.index')
            ->with('success', 'Curso deleted successfully');
    }
}
