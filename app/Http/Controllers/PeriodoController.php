<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PeriodoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $periodos = Periodo::paginate();

        return view('periodo.index', compact('periodos'))
            ->with('i', ($request->input('page', 1) - 1) * $periodos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $periodo = new Periodo();

        return view('periodo.create', compact('periodo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PeriodoRequest $request): RedirectResponse
    {
        Periodo::create($request->validated());

        return Redirect::route('periodos.index')
            ->with('success', 'Periodo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $periodo = Periodo::find($id);

        return view('periodo.show', compact('periodo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $periodo = Periodo::find($id);

        return view('periodo.edit', compact('periodo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PeriodoRequest $request, Periodo $periodo): RedirectResponse
    {
        $periodo->update($request->validated());

        return Redirect::route('periodos.index')
            ->with('success', 'Periodo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Periodo::find($id)->delete();

        return Redirect::route('periodos.index')
            ->with('success', 'Periodo deleted successfully');
    }
}
