<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PeriodoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

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
    public function store(PeriodoRequest $request)
    {
        $data = $request->validated();

    // ¿Existe (incluyendo los dados de baja)?
        $existente = Periodo::withTrashed()
            ->where('anio', $data['anio'])
            ->where('nombre', $data['nombre'])
            ->first();

        if ($existente) {
            if ($existente->trashed()) {
                $existente->restore();                 // ✅ reactivar
                // (si tuvieras más campos, aquí podrías actualizarlos)
                return redirect()->route('periodos.index')
                    ->with('success','Periodo reactivado.');
            }
        // Ya existe activo → mensaje de validación
            throw ValidationException::withMessages([
                'nombre' => 'Ya existe ese periodo para ese año.',
            ]);
        }

        Periodo::create($data);
        return redirect()->route('periodos.index')->with('success','Periodo creado.');
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
    public function update(PeriodoRequest $request, Periodo $periodo)
    {
        $periodo->update($request->validated());
        return redirect()->route('periodos.index')->with('success','Periodo actualizado.');
    }

    public function destroy(Periodo $periodo)
    {
        $periodo->delete();
        return redirect()->route('periodos.index')->with('success','Periodo dado de baja.');
    }
}
