<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AlumnoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Carrera;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $alumnos = \App\Models\Alumno::with('carrera')->paginate();

        return view('alumno.index', compact('alumnos'))
            ->with('i', ($request->input('page', 1) - 1) * $alumnos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alumno = new \App\Models\Alumno();
        $carreras = Carrera::orderBy('nombre_carr')
        ->pluck('nombre_carr', 'id_carrera'); // [id => nombre]

        return view('alumno.create', compact('alumno','carreras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlumnoRequest $request): RedirectResponse
    {
        \App\Models\Alumno::create($request->validated());
        return redirect()->route('alumnos.index')->with('success', 'Alumno creado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Alumno $alumno)
    {
        $alumno->load('carrera');  // para evitar N+1
        return view('alumno.show', compact('alumno'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Alumno $alumno)
    {
        $carreras = Carrera::orderBy('nombre_carr')
        ->pluck('nombre_carr', 'id_carrera');

        return view('alumno.edit', compact('alumno','carreras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlumnoRequest $request, Alumno $alumno): RedirectResponse
    {
        $alumno->update($request->validated());

        return redirect()->route('alumnos.index')->with('success', 'Alumno actualizado.');
    }

    public function destroy($id): RedirectResponse
    {
        Alumno::find($id)->delete();

        return Redirect::route('alumnos.index')
            ->with('success', 'Alumno deleted successfully');
    }
}
