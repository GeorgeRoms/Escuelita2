<?php

namespace App\Http\Controllers;

use App\Models\ContactosAlumno;
use App\Models\Alumno;
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
        $contactosAlumnos = ContactosAlumno::with('alumno')  // para mostrar nombre sin N+1
            ->orderBy('id_contacto')
            ->paginate();

        return view('contactos-alumno.index', compact('contactosAlumnos'))
            ->with('i', ($request->input('page', 1) - 1) * $contactosAlumnos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contactosAlumno = new ContactosAlumno();

        // no_control => "no_control — Nombre Apellido"
        $alumnos = Alumno::orderBy('no_control')->get()
            ->mapWithKeys(function ($a) {
                $nombre = trim($a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? ''));
                return [$a->no_control => $a->no_control.' — '.$nombre];
            });

        return view('contactos-alumno.create', compact('contactosAlumno', 'alumnos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactosAlumnoRequest $request): RedirectResponse
    {
        ContactosAlumno::create($request->validated());

        return redirect()->route('contactos-alumnos.index')
            ->with('success', 'Contacto creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactosAlumno $contactos_alumno): View
    {
        $contactos_alumno->load('alumno');

        // Ojo: tu vista espera $contactosAlumno
        return view('contactos-alumno.show', ['contactosAlumno' => $contactos_alumno]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactosAlumno $contactos_alumno): View
    {
        $alumnos = Alumno::orderBy('no_control')->get()
            ->mapWithKeys(function ($a) {
                $nombre = trim($a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? ''));
                return [$a->no_control => $a->no_control.' — '.$nombre];
            });

        return view('contactos-alumno.edit', [
            'contactosAlumno' => $contactos_alumno,
            'alumnos'         => $alumnos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactosAlumnoRequest $request, ContactosAlumno $contactos_alumno): RedirectResponse
    {
        $contactos_alumno->update($request->validated());

        return redirect()->route('contactos-alumnos.index')
            ->with('success', 'Contacto actualizado correctamente.');
    }

    public function destroy($id): RedirectResponse
    {
        ContactosAlumno::find($id)->delete();

        return Redirect::route('contactos-alumnos.index')
            ->with('success', 'ContactosAlumno deleted successfully');
    }
}
