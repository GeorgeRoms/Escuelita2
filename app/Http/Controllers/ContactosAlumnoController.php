<?php

namespace App\Http\Controllers;

use App\Models\ContactosAlumno;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ContactosAlumnoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ContactosAlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $contactosAlumnos = ContactosAlumno::paginate();

        return view('contactos-alumno.index', compact('contactosAlumnos'))
            ->with('i', ($request->input('page', 1) - 1) * $contactosAlumnos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contactosAlumno = new ContactosAlumno();

        return view('contactos-alumno.create', compact('contactosAlumno'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactosAlumnoRequest $request): RedirectResponse
    {
        ContactosAlumno::create($request->validated());

        return Redirect::route('contactos-alumnos.index')
            ->with('success', 'ContactosAlumno created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $contactosAlumno = ContactosAlumno::find($id);

        return view('contactos-alumno.show', compact('contactosAlumno'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $contactosAlumno = ContactosAlumno::find($id);

        return view('contactos-alumno.edit', compact('contactosAlumno'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactosAlumnoRequest $request, ContactosAlumno $contactosAlumno): RedirectResponse
    {
        $contactosAlumno->update($request->validated());

        return Redirect::route('contactos-alumnos.index')
            ->with('success', 'ContactosAlumno updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        ContactosAlumno::find($id)->delete();

        return Redirect::route('contactos-alumnos.index')
            ->with('success', 'ContactosAlumno deleted successfully');
    }
}
