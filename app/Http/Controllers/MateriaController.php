<?php

namespace App\Http\Controllers;

use App\Http\Requests\MateriaRequest;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $materias = Materia::with('prerrequisito')
                    ->orderBy('nombre_mat')
                    ->paginate();

                return [$materias, $request];
            },
            function ($payload) {
                [$materias, $request] = $payload;

                return view('materia.index', compact('materias'))
                    ->with('i', ($request->input('page', 1) - 1) * $materias->perPage());
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
                $materia = new Materia();
                $candidatas = Materia::orderBy('nombre_mat')
                    ->pluck('nombre_mat', 'id_materia'); // [id => nombre]

                return compact('materia', 'candidatas');
            },
            function ($data) {
                return view('materia.create', $data);
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
    public function store(MateriaRequest $request)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($validated) {
                return DB::transaction(function () use ($validated) {
                    return Materia::create($validated);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'materias.index', 'Materia creada.', null, 201);
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Materia $materia)
    {
        return Safe::run(
            function () use ($materia) {
                $materia->load('prerrequisito');
                return compact('materia');
            },
            function ($data) {
                return view('materia.show', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo mostrar la materia. Folio: '.$folio);
            }
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materia $materia)
    {
        return Safe::run(
            function () use ($materia) {
                $candidatas = Materia::where('id_materia', '!=', $materia->id_materia)
                    ->orderBy('nombre_mat')
                    ->pluck('nombre_mat', 'id_materia');

                return compact('materia', 'candidatas');
            },
            function ($data) {
                return view('materia.edit', $data);
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
    public function update(MateriaRequest $request, Materia $materia)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($materia, $validated) {
                return DB::transaction(function () use ($materia, $validated) {
                    $materia->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'materias.index', 'Materia actualizada.');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy(Materia $materia)
    {
        return Safe::run(
            function () use ($materia) {
                return DB::transaction(function () use ($materia) {
                    $materia->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('materias.index')
                    ->with('success', 'Materia borrada satisfactoriamente.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar la materia. Folio: '.$folio);
            }
        );
    }
}

