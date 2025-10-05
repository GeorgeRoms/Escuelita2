<?php

namespace App\Http\Controllers;

use App\Models\Profesore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ProfesoreRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfesoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $profesores = Profesore::paginate();

        return view('profesore.index', compact('profesores'))
            ->with('i', ($request->input('page', 1) - 1) * $profesores->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $profesore = new Profesore();

        return view('profesore.create', compact('profesore'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfesoreRequest $request): RedirectResponse
    {
        Profesore::create($request->validated());

        return Redirect::route('profesores.index')
            ->with('success', 'Profesore created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $profesore = Profesore::find($id);

        return view('profesore.show', compact('profesore'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $profesore = Profesore::find($id);

        return view('profesore.edit', compact('profesore'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfesoreRequest $request, Profesore $profesore): RedirectResponse
    {
        $profesore->update($request->validated());

        return Redirect::route('profesores.index')
            ->with('success', 'Profesore updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Profesore::find($id)->delete();

        return Redirect::route('profesores.index')
            ->with('success', 'Profesore deleted successfully');
    }
}
