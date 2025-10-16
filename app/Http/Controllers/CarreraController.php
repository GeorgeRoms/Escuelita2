<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use App\Http\Requests\CarreraRequest;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;

class CarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $carreras = Carrera::paginate();
                return [$carreras, $request];
            },
            function ($payload) {
                [$carreras, $request] = $payload;
                return view('carrera.index', compact('carreras'))
                    ->with('i', ($request->input('page', 1) - 1) * $carreras->perPage());
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
                $carrera = new Carrera();
                return compact('carrera');
            },
            function ($data) {
                return view('carrera.create', $data);
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
    public function store(CarreraRequest $request)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($validated) {
                return DB::transaction(function () use ($validated) {
                    return Carrera::create($validated);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'carreras.index', 'Carrera created successfully.', null, 201);
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id_carrera)
    {
        return Safe::run(
            function () use ($id_carrera) {
                $carrera = Carrera::find($id_carrera);
                return compact('carrera');
            },
            function ($data) {
                return view('carrera.show', $data);
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
    public function edit($id_carrera)
    {
        return Safe::run(
            function () use ($id_carrera) {
                $carrera = Carrera::find($id_carrera);
                return compact('carrera');
            },
            function ($data) {
                return view('carrera.edit', $data);
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
    public function update(CarreraRequest $request, Carrera $carrera)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($carrera, $validated) {
                return DB::transaction(function () use ($carrera, $validated) {
                    $carrera->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'carreras.index', 'Carrera updated successfully');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy($id_carrera)
    {
        return Safe::run(
            function () use ($id_carrera) {
                return DB::transaction(function () use ($id_carrera) {
                    Carrera::find($id_carrera)->delete(); // misma lógica que tenías
                    return true;
                });
            },
            function () {
                return redirect()
                    ->route('carreras.index')
                    ->with('success', 'Carrera deleted successfully');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar la carrera. Folio: '.$folio);
            }
        );
    }
}
