<?php

namespace App\Http\Controllers;

use App\Models\Edificio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EdificioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
    public function store(EdificioRequest $request): RedirectResponse
    {
        Edificio::create($request->validated());

        return Redirect::route('edificios.index')
            ->with('success', 'Edificio created successfully.');
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
            ->with('success', 'Edificio updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Edificio::find($id)->delete();

        return Redirect::route('edificios.index')
            ->with('success', 'Edificio deleted successfully');
    }
}
