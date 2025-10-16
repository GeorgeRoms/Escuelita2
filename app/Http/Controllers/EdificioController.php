<?php

namespace App\Http\Controllers;

use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\EdificioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Support\Safe;
use App\Support\Responder;

class EdificioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $edificios = Edificio::paginate();
                return [$edificios, $request];
            },
            function ($payload) {
                [$edificios, $request] = $payload;

                return view('edificio.index', compact('edificios'))
                    ->with('i', ($request->input('page', 1) - 1) * $edificios->perPage());
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
                $edificio = new Edificio();
                return compact('edificio');
            },
            function ($data) {
                return view('edificio.create', $data);
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
    public function store(EdificioRequest $request)
    {
        $data = $request->validated();

        return Safe::run(
            function () use ($data) {
                return DB::transaction(function () use ($data) {

                    // ¿Existe (aunque esté borrado lógicamente)?
                    $existente = Edificio::withTrashed()
                        ->where('codigo', $data['codigo'])
                        ->first();

                    if ($existente) {
                        if ($existente->trashed()) {
                            // Reactivar y actualizar datos
                            $existente->restore();
                            $existente->update([
                                'nombre' => $data['nombre'],
                            ]);
                            // devolvemos string para onOk
                            return 'reactivado';
                        }

                        // Ya existe activo → error amable (validación)
                        throw ValidationException::withMessages([
                            'codigo' => 'Ya existe un edificio con este código.',
                        ]);
                    }

                    // No existía → crear
                    Edificio::create($data);
                    return 'creado';
                });
            },
            function ($resultado) use ($request) {
                // Mensaje según el flujo que tomó la transacción
                if ($resultado === 'reactivado') {
                    return Responder::ok($request, 'edificios.index', 'Edificio reactivado y actualizado.', null, 200);
                }
                return Responder::ok($request, 'edificios.index', 'Edificio creado.', null, 201);
            },
            function ($folio, $e) use ($request) {
                // Si fue una ValidationException, la relanzamos para que el form la muestre
                if ($e instanceof ValidationException) {
                    throw $e;
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
                $edificio = Edificio::find($id);
                return compact('edificio');
            },
            function ($data) {
                return view('edificio.show', $data);
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
                $edificio = Edificio::find($id);
                return compact('edificio');
            },
            function ($data) {
                return view('edificio.edit', $data);
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
    public function update(EdificioRequest $request, Edificio $edificio)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($edificio, $validated) {
                return DB::transaction(function () use ($edificio, $validated) {
                    $edificio->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'edificios.index', 'Edificio actualizado correctamente');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy(\App\Models\Edificio $edificio)
    {
        return Safe::run(
            function () use ($edificio) {
                return DB::transaction(function () use ($edificio) {
                    // baja lógica; NO toca las aulas
                    $edificio->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('edificios.index')->with('success','Edificio deshabilitado.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo deshabilitar el edificio. Folio: '.$folio);
            }
        );
    }
}

