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
use Illuminate\Support\Facades\DB;

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

    private function edificiosCatalog(?int $currentId = null)
{
    // etiqueta: usa codigo, luego nombre, si no existe ninguno muestra "Edificio #id"
    $base = Edificio::query()
        ->select('id', DB::raw("COALESCE(codigo, nombre, CONCAT('Edificio #', id)) AS etiqueta"))
        ->orderBy('etiqueta')
        ->pluck('etiqueta', 'id');

    // Si estás editando y el edificio actual no está en el catálogo,
    // lo agregamos manualmente para que aparezca preseleccionado.
    if ($currentId && !$base->has($currentId)) {
        $e = Edificio::withTrashed()->find($currentId);
        if ($e) {
            $label = $e->codigo ?? ($e->nombre ?? ('Edificio #'.$e->id));
            $base = $base->put($e->id, $label);
        }
    }

    return $base;
}

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $area = new Area();

        $edificios  = $this->edificiosCatalog();       // sin valor actual
        $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
            ->mapWithKeys(fn($p) => [
                $p->id_profesor => trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''))
        ]);

        return view('area.create', compact('area','edificios','profesores'));
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
        $edificios  = $this->edificiosCatalog($area->edificio_id); // incluye actual si no está
        $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
            ->mapWithKeys(fn($p) => [
                $p->id_profesor => trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''))
        ]);

        return view('area.edit', compact('area','edificios','profesores'));
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

if (!function_exists('column_exists')) {
    function column_exists(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}