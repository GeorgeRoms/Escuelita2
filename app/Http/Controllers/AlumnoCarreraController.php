<?php

namespace App\Http\Controllers;

use App\Models\AlumnoCarrera;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AlumnoCarreraRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AlumnoCarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $alumnoCarreras = AlumnoCarrera::paginate();

        return view('alumno-carrera.index', compact('alumnoCarreras'))
            ->with('i', ($request->input('page', 1) - 1) * $alumnoCarreras->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $alumnoCarrera = new AlumnoCarrera();

        return view('alumno-carrera.create', compact('alumnoCarrera'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlumnoCarreraRequest $request): RedirectResponse
    {
        AlumnoCarrera::create($request->validated());

        return Redirect::route('alumno-carreras.index')
            ->with('success', 'AlumnoCarrera created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $alumnoCarrera = AlumnoCarrera::find($id);

        return view('alumno-carrera.show', compact('alumnoCarrera'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $alumnoCarrera = AlumnoCarrera::find($id);

        return view('alumno-carrera.edit', compact('alumnoCarrera'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlumnoCarreraRequest $request, AlumnoCarrera $alumnoCarrera): RedirectResponse
    {
        $alumnoCarrera->update($request->validated());

        return Redirect::route('alumno-carreras.index')
            ->with('success', 'AlumnoCarrera updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        AlumnoCarrera::find($id)->delete();

        return Redirect::route('alumno-carreras.index')
            ->with('success', 'AlumnoCarrera deleted successfully');
    }
}
