<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreaRequest;
use App\Models\Area;
use App\Models\Edificio;
use App\Models\Profesore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                // Cargamos relaciones para evitar N+1 y ordenamos por nombre
                $areas = Area::with(['edificio', 'jefe'])
                    ->orderBy('nombre_area')
                    ->paginate();

                return [$areas, $request];
            },
            function ($payload) {
                [$areas, $request] = $payload;

                return view('area.index', compact('areas'))
                    ->with('i', ($request->input('page', 1) - 1) * $areas->perPage());
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
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
    public function create()
    {
        return Safe::run(
            function () {
                $area = new Area();

                $edificios  = $this->edificiosCatalog(); // sin valor actual
                $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
                    ->mapWithKeys(fn($p) => [
                        $p->id_profesor => trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''))
                    ]);

                return compact('area','edificios','profesores');
            },
            function ($data) {
                return view('area.create', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo cargar el formulario. Folio: '.$folio);
            }
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AreaRequest $request)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($validated) {
                return DB::transaction(function () use ($validated) {
                    return Area::create($validated);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'areas.index', 'Área creada correctamente.', null, 201);
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        return Safe::run(
            function () use ($area) {
                // Cargamos relaciones para mostrar nombres en el show
                $area->load(['edificio', 'jefe']);
                return $area;
            },
            function ($area) {
                return view('area.show', compact('area'));
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo mostrar el registro. Folio: '.$folio);
            }
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return Safe::run(
            function () use ($area) {
                $edificios  = $this->edificiosCatalog($area->edificio_id); // incluye actual si no está
                $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
                    ->mapWithKeys(fn($p) => [
                        $p->id_profesor => trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''))
                    ]);

                return compact('area','edificios','profesores');
            },
            function ($data) {
                return view('area.edit', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo cargar la edición. Folio: '.$folio);
            }
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AreaRequest $request, Area $area)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($area, $validated) {
                return DB::transaction(function () use ($area, $validated) {
                    $area->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'areas.index', 'Área actualizada correctamente.');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        return Safe::run(
            function () use ($area) {
                return DB::transaction(function () use ($area) {
                    $area->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('areas.index')
                    ->with('success', 'Área eliminada correctamente.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar el área. Folio: '.$folio);
            }
        );
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