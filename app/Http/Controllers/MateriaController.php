<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MateriaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $materias = Materia::paginate();

        return view('materia.index', compact('materias'))
            ->with('i', ($request->input('page', 1) - 1) * $materias->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $materia = new Materia();

        return view('materia.create', compact('materia'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MateriaRequest $request): RedirectResponse
    {
        Materia::create($request->validated());

        return Redirect::route('materias.index')
            ->with('success', 'Materia created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $materia = Materia::find($id);

        return view('materia.show', compact('materia'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $materia = Materia::find($id);

        return view('materia.edit', compact('materia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MateriaRequest $request, Materia $materia): RedirectResponse
    {
        $materia->update($request->validated());

        return Redirect::route('materias.index')
            ->with('success', 'Materia updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Materia::find($id)->delete();

        return Redirect::route('materias.index')
            ->with('success', 'Materia deleted successfully');
    }
}
