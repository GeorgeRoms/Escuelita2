<?php

namespace App\Http\Controllers;

use App\Models\Inscripcione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\InscripcioneRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class InscripcioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $inscripciones = Inscripcione::paginate();

        return view('inscripcione.index', compact('inscripciones'))
            ->with('i', ($request->input('page', 1) - 1) * $inscripciones->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $inscripcione = new Inscripcione();

        return view('inscripcione.create', compact('inscripcione'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InscripcioneRequest $request)
    {
        $data = $request->validated();
        $insc = \App\Models\Inscripcione::firstOrCreate(
            ['alumno_no_control'=>$data['alumno_no_control'], 'curso_id'=>$data['curso_id']],
            ['fecha'=>$data['fecha'] ?? now()->toDateString(), 'estatus'=>$data['estatus'] ?? 'Inscrito']
        );
        return back()->with('ok','Inscripción registrada.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $inscripcione = Inscripcione::find($id);

        return view('inscripcione.show', compact('inscripcione'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $inscripcione = Inscripcione::find($id);

        return view('inscripcione.edit', compact('inscripcione'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(InscripcioneRequest $request, Inscripcione $inscripcione): RedirectResponse
    {
        $inscripcione->update($request->validated());

        return Redirect::route('inscripciones.index')
            ->with('success', 'Inscripcione updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Inscripcione::find($id)->delete();

        return Redirect::route('inscripciones.index')
            ->with('success', 'Inscripcione deleted successfully');
    }
}
