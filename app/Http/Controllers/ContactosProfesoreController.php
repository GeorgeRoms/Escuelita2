<?php

namespace App\Http\Controllers;

use App\Models\ContactosProfesore;
use App\Models\Profesore;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContactosProfesoreRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;

class ContactosProfesoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $contactosProfesores = ContactosProfesore::with('profesor')
                    ->orderBy('id_contacto')
                    ->paginate();

                // Paso ambos nombres por si tu blade usa uno u otro
                return [
                    'contactosProfesores' => $contactosProfesores,
                    'contactos'           => $contactosProfesores,
                    'i'                   => ($request->input('page', 1) - 1) * $contactosProfesores->perPage(),
                ];
            },
            function ($data) {
                return view('contactos-profesore.index', $data);
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
                $contactosProfesore = new ContactosProfesore();

                $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
                    ->mapWithKeys(function ($p) {
                        $nom = trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
                        return [$p->id_profesor => $nom];
                    });

                return [
                    // objeto del form con varios alias
                    'contactosProfesore' => $contactosProfesore,
                    'contacto'           => $contactosProfesore,
                    // catálogo con dos alias
                    'profesores'        => $profesores,
                    'catalProfesores'   => $profesores,
                ];
            },
            function ($data) {
                return view('contactos-profesore.create', $data);
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
    public function store(ContactosProfesoreRequest $request)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($validated) {
                return DB::transaction(function () use ($validated) {
                    return ContactosProfesore::create($validated);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'contactos-profesores.index', 'Contacto creado.', null, 201);
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function show(ContactosProfesore $contactos_profesore)
    {
        return Safe::run(
            function () use ($contactos_profesore) {
                $contactos_profesore->load('profesor');

                return [
                    'contactosProfesore' => $contactos_profesore,
                    'contacto'           => $contactos_profesore,
                ];
            },
            function ($data) {
                return view('contactos-profesore.show', $data);
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
    public function edit(ContactosProfesore $contactos_profesore)
    {
        return Safe::run(
            function () use ($contactos_profesore) {
                $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
                    ->mapWithKeys(function ($p) {
                        $nom = trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
                        return [$p->id_profesor => $nom];
                    });

                return [
                    'contactosProfesore' => $contactos_profesore,
                    'contacto'           => $contactos_profesore,
                    'profesores'         => $profesores,
                    'catalProfesores'    => $profesores,
                ];
            },
            function ($data) {
                return view('contactos-profesore.edit', $data);
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo cargar la edición. Folio: '.$folio);
            }
        );
    }

    public function update(ContactosProfesoreRequest $request, ContactosProfesore $contactos_profesore)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($contactos_profesore, $validated) {
                return DB::transaction(function () use ($contactos_profesore, $validated) {
                    $contactos_profesore->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'contactos-profesores.index', 'Contacto actualizado.');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy(ContactosProfesore $contactos_profesore): RedirectResponse
    {
        return Safe::run(
            function () use ($contactos_profesore) {
                return DB::transaction(function () use ($contactos_profesore) {
                    $contactos_profesore->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('contactos-profesores.index')
                    ->with('success', 'Contacto eliminado.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar el contacto. Folio: '.$folio);
            }
        );
    }
}

