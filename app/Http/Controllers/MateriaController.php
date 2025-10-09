<?php

namespace App\Http\Controllers;

use App\Http\Requests\MateriaRequest;
use App\Models\Materia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\View\View
    {
        $materias = \App\Models\Materia::with('prerrequisito') 
        ->orderBy('nombre_mat')
        ->paginate();

    return view('materia.index', compact('materias'))
        ->with('i', ($request->input('page', 1) - 1) * $materias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $materia = new Materia(); // <- para que el form tenga el objeto
        $candidatas = Materia::orderBy('nombre_mat')
            ->pluck('nombre_mat', 'id_materia'); // [id => nombre]

        return view('materia.create', compact('materia', 'candidatas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MateriaRequest $request): RedirectResponse
    {
        Materia::create($request->validated());
        return redirect()->route('materias.index')->with('success', 'Materia creada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Materia $materia): View
    {
        // si quieres ver también el prerrequisito:
        $materia->load('prerrequisito');
        return view('materia.show', compact('materia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materia $materia): View
    {
        $candidatas = Materia::where('id_materia', '!=', $materia->id_materia)
            ->orderBy('nombre_mat')
            ->pluck('nombre_mat', 'id_materia');

        return view('materia.edit', compact('materia', 'candidatas'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(MateriaRequest $request, Materia $materia): RedirectResponse
    {
        $materia->update($request->validated());
        return redirect()->route('materias.index')->with('success', 'Materia actualizada.');
    }

    public function destroy(Materia $materia): RedirectResponse
    {
        $materia->delete();
        return redirect()->route('materias.index')->with('success', 'Materia borrada satisfactoriamente.');
    }
}
