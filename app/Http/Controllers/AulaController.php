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
    public function index(Request $request): View
    {
        $aulas = Aula::paginate();

        return view('aula.index', compact('aulas'))
            ->with('i', ($request->input('page', 1) - 1) * $aulas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $aula = new Aula();

        return view('aula.create', compact('aula'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AulaRequest $request)
    {
        \App\Models\Aula::create($request->validated());
        return back()->with('ok','Aula creada.');
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
    public function edit($id): View
    {
        $aula = Aula::find($id);

        return view('aula.edit', compact('aula'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AulaRequest $request, Aula $aula): RedirectResponse
    {
        $aula->update($request->validated());

        return Redirect::route('aulas.index')
            ->with('success', 'Aula updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Aula::find($id)->delete();

        return Redirect::route('aulas.index')
            ->with('success', 'Aula deleted successfully');
    }
}
