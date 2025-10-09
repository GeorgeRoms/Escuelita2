<?php

namespace App\Http\Controllers;

use App\Models\Profesore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProfesoreRequest;
use App\Models\Area;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfesoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $profesores = Profesore::with('area')   // para mostrar nombre de área sin N+1
            ->orderBy('nombre')
            ->paginate();

        return view('profesore.index', compact('profesores'))
            ->with('i', ($request->input('page', 1) - 1) * $profesores->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $profesore = new Profesore(); // <- FALTABA
        $catalAreas = Area::orderBy('nombre_area')->pluck('nombre_area','id_area');

        return view('profesore.create', compact('profesore', 'catalAreas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfesoreRequest $request): RedirectResponse
    {
        Profesore::create($request->validated());

        return redirect()->route('profesores.index')
            ->with('success', 'Profesor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profesore $profesore): View
    {
        $profesore->load('area');
        return view('profesore.show', compact('profesore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profesore $profesore): View
    {
        $catalAreas = Area::orderBy('nombre_area')->pluck('nombre_area','id_area');

        return view('profesore.edit', compact('profesore', 'catalAreas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfesoreRequest $request, Profesore $profesore): RedirectResponse
    {
        $profesore->update($request->validated());

        return redirect()->route('profesores.index')
            ->with('success', 'Profesor actualizado.');
    }

    public function destroy(Profesore $profesore): RedirectResponse
    {
        $profesore->delete();

        return redirect()->route('profesores.index')
            ->with('success', 'Profesor eliminado.');
    }
}
