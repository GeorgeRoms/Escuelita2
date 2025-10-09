<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreaRequest;
use App\Models\Area;
use App\Models\Edificio;
use App\Models\Profesore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // Cargamos relaciones para evitar N+1 y ordenamos por nombre
        $areas = Area::with(['edificio', 'jefe'])
            ->orderBy('nombre_area')
            ->paginate();

        return view('area.index', compact('areas'))
            ->with('i', ($request->input('page', 1) - 1) * $areas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $area = new Area();

        // Catálogo de edificios: id => etiqueta legible
        // Asumimos PK = 'edificio' y campo 'salon' (según tu esquema).
        $edificios = Edificio::orderBy('edificio')->get()
            ->mapWithKeys(function ($e) {
                $label = $e->salon ? "Edificio {$e->edificio} — {$e->salon}" : "Edificio {$e->edificio}";
                return [$e->edificio => $label];
            });

        // Catálogo de profesores: id_profesor => "Nombre Apellidos"
        $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
            ->mapWithKeys(function ($p) {
                $nom = trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
                return [$p->id_profesor => $nom];
            });

        // Paso ambos alias para que tus blades funcionen sin cambios
        return view('area.create', [
            'area'             => $area,
            'edificios'        => $edificios,
            'catalEdificios'   => $edificios,
            'profesores'       => $profesores,
            'catalProfesores'  => $profesores,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AreaRequest $request): RedirectResponse
    {
        Area::create($request->validated());

        return Redirect::route('areas.index')
            ->with('success', 'Área creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area): View
    {
        // Cargamos relaciones para mostrar nombres en el show
        $area->load(['edificio', 'jefe']);

        return view('area.show', compact('area'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area): View
    {
        // Catálogos (mismo criterio que en create)
        $edificios = Edificio::orderBy('edificio')->get()
            ->mapWithKeys(function ($e) {
                $label = $e->salon ? "Edificio {$e->edificio} — {$e->salon}" : "Edificio {$e->edificio}";
                return [$e->edificio => $label];
            });

        $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
            ->mapWithKeys(function ($p) {
                $nom = trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
                return [$p->id_profesor => $nom];
            });

        return view('area.edit', [
            'area'             => $area,
            'edificios'        => $edificios,
            'catalEdificios'   => $edificios,
            'profesores'       => $profesores,
            'catalProfesores'  => $profesores,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AreaRequest $request, Area $area): RedirectResponse
    {
        $area->update($request->validated());

        return Redirect::route('areas.index')
            ->with('success', 'Área actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area): RedirectResponse
    {
        $area->delete();

        return Redirect::route('areas.index')
            ->with('success', 'Área eliminada correctamente.');
    }
}
