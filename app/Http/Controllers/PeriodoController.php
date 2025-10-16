<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\PeriodoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Support\Safe;
use App\Support\Responder;

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $periodos = Periodo::paginate();
                return [$periodos, $request];
            },
            function ($payload) {
                [$periodos, $request] = $payload;
                return view('periodo.index', compact('periodos'))
                    ->with('i', ($request->input('page', 1) - 1) * $periodos->perPage());
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
                $periodo = new Periodo();
                return compact('periodo');
            },
            function ($data) {
                return view('periodo.create', $data);
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
    public function store(PeriodoRequest $request)
    {
        $data = $request->validated();

        return Safe::run(
            function () use ($data) {
                return DB::transaction(function () use ($data) {
                    // ¿Existe (incluyendo los dados de baja)?
                    $existente = Periodo::withTrashed()
                        ->where('anio', $data['anio'])
                        ->where('nombre', $data['nombre'])
                        ->first();

                    if ($existente) {
                        if ($existente->trashed()) {
                            // Reactivar
                            $existente->restore();
                            return 'reactivado';
                        }

                        // Ya existe activo → mensaje de validación
                        throw ValidationException::withMessages([
                            'nombre' => 'Ya existe ese periodo para ese año.',
                        ]);
                    }

                    // Crear nuevo periodo
                    Periodo::create($data);
                    return 'creado';
                });
            },
            function ($resultado) use ($request) {
                if ($resultado === 'reactivado') {
                    return Responder::ok($request, 'periodos.index', 'Periodo reactivado.', null, 200);
                }
                return Responder::ok($request, 'periodos.index', 'Periodo creado.', null, 201);
            },
            function ($folio, $e) use ($request) {
                if ($e instanceof ValidationException) {
                    throw $e; // deja que Laravel muestre los errores en el form
                }
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Safe::run(
            function () use ($id) {
                $periodo = Periodo::find($id);
                return compact('periodo');
            },
            function ($data) {
                return view('periodo.show', $data);
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
    public function edit($id)
    {
        return Safe::run(
            function () use ($id) {
                $periodo = Periodo::find($id);
                return compact('periodo');
            },
            function ($data) {
                return view('periodo.edit', $data);
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
    public function update(PeriodoRequest $request, Periodo $periodo)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($periodo, $validated) {
                return DB::transaction(function () use ($periodo, $validated) {
                    $periodo->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'periodos.index', 'Periodo actualizado.');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy(Periodo $periodo)
    {
        return Safe::run(
            function () use ($periodo) {
                return DB::transaction(function () use ($periodo) {
                    $periodo->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('periodos.index')
                    ->with('success', 'Periodo dado de baja.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo dar de baja el periodo. Folio: '.$folio);
            }
        );
    }
}

