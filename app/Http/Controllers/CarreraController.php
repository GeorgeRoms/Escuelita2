<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CarreraRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $carreras = Carrera::paginate();

        return view('carrera.index', compact('carreras'))
            ->with('i', ($request->input('page', 1) - 1) * $carreras->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $carrera = new Carrera();

        return view('carrera.create', compact('carrera'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarreraRequest $request): RedirectResponse
    {
        Carrera::create($request->validated());

        return Redirect::route('carreras.index')
            ->with('success', 'Carrera created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id_carrera): View
    {
        $carrera = Carrera::find($id_carrera);

        return view('carrera.show', compact('carrera'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_carrera): View
    {
        $carrera = Carrera::find($id_carrera);

        return view('carrera.edit', compact('carrera'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarreraRequest $request, Carrera $carrera): RedirectResponse
    {
        $carrera->update($request->validated());

        return Redirect::route('carreras.index')
            ->with('success', 'Carrera updated successfully');
    }

    public function destroy($id_carrera): RedirectResponse
    {
        Carrera::find($id_carrera)->delete();

        return Redirect::route('carreras.index')
            ->with('success', 'Carrera deleted successfully');
    }
}
