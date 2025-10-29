<?php

namespace App\Http\Controllers;

use App\Models\Profesore;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProfesoreRequest;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;

class ProfesoreController extends Controller
{
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $profesores = Profesore::with('area')   // para mostrar nombre de área sin N+1
                    ->orderBy('nombre')
                    ->paginate();

                return [$profesores, $request];
            },
            function ($payload) {
                [$profesores, $request] = $payload;

                return view('profesore.index', compact('profesores'))
                    ->with('i', ($request->input('page', 1) - 1) * $profesores->perPage());
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Safe::run(
            function () {
                $profesore = new Profesore();
                $catalAreas = Area::orderBy('nombre_area')->pluck('nombre_area','id_area');

                return compact('profesore', 'catalAreas');
            },
            function ($data) {
                return view('profesore.create', $data);
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
    public function store(ProfesoreRequest $request)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($validated) {
                return DB::transaction(function () use ($validated) {
                    return Profesore::create($validated);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'profesores.index', 'Profesor creado correctamente.', null, 201);
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Profesore $profesore)
    {
        return Safe::run(
            function () use ($profesore) {
                $profesore->load('area');
                return compact('profesore');
            },
            function ($data) {
                return view('profesore.show', $data);
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
    public function edit(Profesore $profesore)
    {
        return Safe::run(
            function () use ($profesore) {
                $catalAreas = Area::orderBy('nombre_area')->pluck('nombre_area','id_area');
                return compact('profesore', 'catalAreas');
            },
            function ($data) {
                return view('profesore.edit', $data);
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
    public function update(ProfesoreRequest $request, Profesore $profesore)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($profesore, $validated) {
                return DB::transaction(function () use ($profesore, $validated) {
                    $profesore->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'profesores.index', 'Profesor actualizado.');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy(Profesore $profesore)
    {
        return Safe::run(
            function () use ($profesore) {
                return DB::transaction(function () use ($profesore) {
                    $profesore->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('profesores.index')
                    ->with('success', 'Profesor eliminado.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar el profesor. Folio: '.$folio);
            }
        );
    }
}