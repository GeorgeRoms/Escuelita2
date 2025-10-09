<?php

namespace App\Http\Controllers;

use App\Models\ContactosProfesore;
use App\Models\Profesore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ContactosProfesoreRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ContactosProfesoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $contactosProfesores = ContactosProfesore::with('profesor')
            ->orderBy('id_contacto')->paginate();

        // Paso ambos nombres por si tu blade usa uno u otro
        return view('contactos-profesore.index', [
            'contactosProfesores' => $contactosProfesores,
            'contactos'           => $contactosProfesores,
            'i'                   => ($request->input('page', 1) - 1) * $contactosProfesores->perPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contactosProfesore = new ContactosProfesore();

        $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
            ->mapWithKeys(function ($p) {
                $nom = trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
                return [$p->id_profesor => $nom];
            });

        return view('contactos-profesore.create', [
            // objeto del form con varios alias
            'contactosProfesore' => $contactosProfesore,
            'contacto'           => $contactosProfesore,
            // catálogo con dos alias
            'profesores'        => $profesores,
            'catalProfesores'   => $profesores,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactosProfesoreRequest $request): RedirectResponse
    {
        ContactosProfesore::create($request->validated());
        return redirect()->route('contactos-profesores.index')->with('success', 'Contacto creado.');
    }

    public function show(ContactosProfesore $contactos_profesore): View
    {
        $contactos_profesore->load('profesor');

        return view('contactos-profesore.show', [
            'contactosProfesore' => $contactos_profesore,
            'contacto'           => $contactos_profesore,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactosProfesore $contactos_profesore): View
    {
        $profesores = Profesore::orderBy('nombre')->orderBy('apellido_pat')->get()
            ->mapWithKeys(function ($p) {
                $nom = trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
                return [$p->id_profesor => $nom];
            });

        return view('contactos-profesore.edit', [
            'contactosProfesore' => $contactos_profesore,
            'contacto'           => $contactos_profesore,
            'profesores'         => $profesores,
            'catalProfesores'    => $profesores,
        ]);
    }

    public function update(ContactosProfesoreRequest $request, ContactosProfesore $contactos_profesore): RedirectResponse
    {
        $contactos_profesore->update($request->validated());
        return redirect()->route('contactos-profesores.index')->with('success', 'Contacto actualizado.');
    }

    public function destroy(ContactosProfesore $contactos_profesore): RedirectResponse
    {
        $contactos_profesore->delete();
        return redirect()->route('contactos-profesores.index')->with('success', 'Contacto eliminado.');
    }
}
