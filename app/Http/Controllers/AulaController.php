<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AulaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $aulas = \App\Models\Aula::with('edificio')
            ->join('edificios','edificios.id','=','aulas.edificio_id') // sólo para ordenar bonito
            ->orderBy('edificios.codigo')->orderBy('aulas.salon')
            ->select('aulas.*') // importante para no romper el paginator
            ->paginate();

        return view('aula.index', compact('aulas'))
            ->with('i', ($request->input('page', 1) - 1) * $aulas->perPage());
    }
    private function catalogoEdificios(): array
    {
        return \App\Models\Edificio::orderBy('codigo')->orderBy('nombre')->get()
            ->mapWithKeys(fn($e) => [$e->id => ($e->codigo.' — '.$e->nombre)])
            ->toArray();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $aula = new \App\Models\Aula();
        $edificios = $this->catalogoEdificios();
        return view('aula.create', compact('aula','edificios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AulaRequest $request)
    {
        Aula::create($request->validated());

        return redirect()
            ->route('aulas.index')
            ->with('success', 'Aula registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $aula = Aula::find($id);

        return view('aula.show', compact('aula'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Aula $aula)
    {
        $edificios = $this->catalogoEdificios();
        return view('aula.edit', compact('aula','edificios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AulaRequest $request, Aula $aula)
    {
        $aula->update($request->validated());

        return redirect()
            ->route('aulas.index')
            ->with('success', 'Aula actualizada.');
    }

    public function destroy($id): RedirectResponse
    {
        Aula::find($id)->delete();

        return Redirect::route('aulas.index')
            ->with('success', 'Aula deleted successfully');
    }
}
