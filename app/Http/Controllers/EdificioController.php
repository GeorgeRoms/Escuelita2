<?php

namespace App\Http\Controllers;

use App\Models\Edificio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EdificioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class EdificioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $edificios = Edificio::paginate();

        return view('edificio.index', compact('edificios'))
            ->with('i', ($request->input('page', 1) - 1) * $edificios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $edificio = new Edificio();

        return view('edificio.create', compact('edificio'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EdificioRequest $request)
    {
        $data = $request->validated();

    // ¿Existe (aunque esté borrado lógicamente)?
    $existente = Edificio::withTrashed()->where('codigo', $data['codigo'])->first();

    if ($existente) {
        if ($existente->trashed()) {
            // Reactivar y actualizar datos
            $existente->restore();
            $existente->update([
                'nombre' => $data['nombre'],
            ]);

            return redirect()->route('edificios.index')
                ->with('success', 'Edificio reactivado y actualizado.');
        }

        // Ya existe activo → error amable
        throw ValidationException::withMessages([
            'codigo' => 'Ya existe un edificio con este código.',
        ]);
    }

    // No existía → crear
    Edificio::create($data);

    return redirect()->route('edificios.index')->with('success', 'Edificio creado.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $edificio = Edificio::find($id);

        return view('edificio.show', compact('edificio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $edificio = Edificio::find($id);

        return view('edificio.edit', compact('edificio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EdificioRequest $request, Edificio $edificio): RedirectResponse
    {
        $edificio->update($request->validated());

        return Redirect::route('edificios.index')
            ->with('success', 'Edificio actualizado correctamente');
    }

    public function destroy(\App\Models\Edificio $edificio)
    {
        try {
            $edificio->delete(); // baja lógica; NO toca las aulas
            return redirect()->route('edificios.index')->with('success','Edificio deshabilitado.');
        } catch (\Throwable $e) {
            return redirect()->route('edificios.index')
                ->withErrors('No se pudo deshabilitar el edificio: '.$e->getMessage());
    }
    }
}
