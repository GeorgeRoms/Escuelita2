<?php

namespace App\Http\Controllers;

use App\Models\Aula;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AulaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;

class AulaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $aulas = \App\Models\Aula::with('edificio')
                    ->join('edificios','edificios.id','=','aulas.edificio_id') // sólo para ordenar bonito
                    ->orderBy('edificios.codigo')->orderBy('aulas.salon')
                    ->select('aulas.*') // importante para no romper el paginator
                    ->paginate();

                return [$aulas, $request];
            },
            function ($payload) {
                [$aulas, $request] = $payload;

                return view('aula.index', compact('aulas'))
                    ->with('i', ($request->input('page', 1) - 1) * $aulas->perPage());
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    private function catalogoEdificios(): array
    {
        return \App\Models\Edificio::orderBy('codigo')->orderBy('nombre')->get()
            ->mapWithKeys(fn($e) => [$e->id => ($e->codigo.' — '.$e->nombre)])
            ->toArray();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Safe::run(
            function () {
                $aula = new \App\Models\Aula();
                $edificios = $this->catalogoEdificios();
                return compact('aula','edificios');
            },
            function ($data) {
                return view('aula.create', $data);
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
    public function store(AulaRequest $request)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($validated) {
                return DB::transaction(function () use ($validated) {
                    return Aula::create($validated);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'aulas.index', 'Aula registrada correctamente.', null, 201);
            },
            function ($folio) use ($request) {
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
                $aula = Aula::find($id);
                return compact('aula');
            },
            function ($data) {
                return view('aula.show', $data);
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
    public function edit(\App\Models\Aula $aula)
    {
        return Safe::run(
            function () use ($aula) {
                $edificios = $this->catalogoEdificios();
                return compact('aula','edificios');
            },
            function ($data) {
                return view('aula.edit', $data);
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
    public function update(AulaRequest $request, Aula $aula)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($aula, $validated) {
                return DB::transaction(function () use ($aula, $validated) {
                    $aula->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'aulas.index', 'Aula actualizada.');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy($id): RedirectResponse
    {
        return Safe::run(
            function () use ($id) {
                return DB::transaction(function () use ($id) {
                    Aula::find($id)->delete();
                    return true;
                });
            },
            function () {
                return Redirect::route('aulas.index')
                    ->with('success', 'Aula deleted successfully');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar el aula. Folio: '.$folio);
            }
        );
    }
}

