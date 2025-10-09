<?php

namespace App\Http\Controllers;

use App\Http\Requests\KardexRequest;
use App\Models\Kardex;
use App\Models\Alumno;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Traemos relaciones para mostrar nombres en la tabla sin N+1
        $kardexes = Kardex::with(['alumno', 'curso.materia'])
            ->orderByDesc('id_kardex')
            ->paginate();

        return view('kardex.index', compact('kardexes'))
            ->with('i', ($request->input('page', 1) - 1) * $kardexes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $kardex = new Kardex();

        // Catálogo de alumnos: no_control => "no_control — Nombre Apellidos"
        $alumnos = Alumno::orderBy('no_control')->get()
            ->mapWithKeys(function ($a) {
                $nombre = trim($a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? ''));
                return [$a->no_control => $a->no_control.' — '.$nombre];
            });

        // Catálogo de cursos: id_curso => "Nombre Materia" (si existe relación materia)
        $cursos = Curso::with('materia')->orderBy('id_curso')->get()
            ->mapWithKeys(function ($c) {
                $etq = $c->materia->nombre_mat ?? ('Curso '.$c->id_curso);
                return [$c->id_curso => $etq];
            });

        return view('kardex.create', [
            'kardex'       => $kardex,
            'alumnos'      => $alumnos,
            'catalAlumnos' => $alumnos,  // alias por si tus blades usan este nombre
            'cursos'       => $cursos,
            'catalCursos'  => $cursos,   // alias por si tus blades usan este nombre
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KardexRequest $request): RedirectResponse
    {
        Kardex::create($request->validated());

        return Redirect::route('kardexes.index')
            ->with('success', 'Kardex creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kardex $kardex): View
    {
        $kardex->load(['alumno', 'curso.materia']);

        return view('kardex.show', compact('kardex'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kardex $kardex): View
    {
        $alumnos = Alumno::orderBy('no_control')->get()
            ->mapWithKeys(function ($a) {
                $nombre = trim($a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? ''));
                return [$a->no_control => $a->no_control.' — '.$nombre];
            });

        $cursos = Curso::with('materia')->orderBy('id_curso')->get()
            ->mapWithKeys(function ($c) {
                $etq = $c->materia->nombre_mat ?? ('Curso '.$c->id_curso);
                return [$c->id_curso => $etq];
            });

        return view('kardex.edit', [
            'kardex'       => $kardex,
            'alumnos'      => $alumnos,
            'catalAlumnos' => $alumnos,
            'cursos'       => $cursos,
            'catalCursos'  => $cursos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KardexRequest $request, Kardex $kardex): RedirectResponse
    {
        $kardex->update($request->validated());

        return Redirect::route('kardexes.index')
            ->with('success', 'Kardex actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kardex $kardex): RedirectResponse
    {
        $kardex->delete();

        return Redirect::route('kardexes.index')
            ->with('success', 'Kardex eliminado correctamente.');
    }
}
