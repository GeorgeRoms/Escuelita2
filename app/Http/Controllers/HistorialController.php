<?php

namespace App\Http\Controllers;

use App\Models\Historial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\HistorialRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class HistorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $historials = Historial::paginate();

        return view('historial.index', compact('historials'))
            ->with('i', ($request->input('page', 1) - 1) * $historials->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $historial = new Historial();

        return view('historial.create', compact('historial'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HistorialRequest $request): RedirectResponse
    {
        Historial::create($request->validated());

        return Redirect::route('historials.index')
            ->with('success', 'Historial created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $historial = Historial::find($id);

        return view('historial.show', compact('historial'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $historial = Historial::find($id);

        return view('historial.edit', compact('historial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HistorialRequest $request, Historial $historial): RedirectResponse
    {
        $historial->update($request->validated());

        return Redirect::route('historials.index')
            ->with('success', 'Historial updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Historial::find($id)->delete();

        return Redirect::route('historials.index')
            ->with('success', 'Historial deleted successfully');
    }
}
