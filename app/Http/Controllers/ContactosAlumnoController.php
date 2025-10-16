<?php

namespace App\Http\Controllers;

use App\Models\ContactosAlumno;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ContactosAlumnoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;

class ContactosAlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $contactosAlumnos = ContactosAlumno::with('alumno')  // para mostrar nombre sin N+1
                    ->orderBy('id_contacto')
                    ->paginate();

                return [$contactosAlumnos, $request];
            },
            function ($payload) {
                [$contactosAlumnos, $request] = $payload;

                return view('contactos-alumno.index', compact('contactosAlumnos'))
                    ->with('i', ($request->input('page', 1) - 1) * $contactosAlumnos->perPage());
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
                $contactosAlumno = new ContactosAlumno();

                // no_control => "no_control — Nombre Apellido"
                $alumnos = Alumno::orderBy('no_control')->get()
                    ->mapWithKeys(function ($a) {
                        $nombre = trim($a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? ''));
                        return [$a->no_control => $a->no_control.' — '.$nombre];
                    });

                return compact('contactosAlumno', 'alumnos');
            },
            function ($data) {
                return view('contactos-alumno.create', $data);
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
    public function store(ContactosAlumnoRequest $request)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($validated) {
                return DB::transaction(function () use ($validated) {
                    return ContactosAlumno::create($validated);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'contactos-alumnos.index', 'Contacto creado correctamente.', null, 201);
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactosAlumno $contactos_alumno)
    {
        return Safe::run(
            function () use ($contactos_alumno) {
                $contactos_alumno->load('alumno');

                // Ojo: tu vista espera $contactosAlumno
                return ['contactosAlumno' => $contactos_alumno];
            },
            function ($data) {
                return view('contactos-alumno.show', $data);
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
    public function edit(ContactosAlumno $contactos_alumno)
    {
        return Safe::run(
            function () use ($contactos_alumno) {
                $alumnos = Alumno::orderBy('no_control')->get()
                    ->mapWithKeys(function ($a) {
                        $nombre = trim($a->nombre.' '.$a->apellido_pat.' '.($a->apellido_mat ?? ''));
                        return [$a->no_control => $a->no_control.' — '.$nombre];
                    });

                return [
                    'contactosAlumno' => $contactos_alumno,
                    'alumnos'         => $alumnos,
                ];
            },
            function ($data) {
                return view('contactos-alumno.edit', $data);
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
    public function update(ContactosAlumnoRequest $request, ContactosAlumno $contactos_alumno)
    {
        $validated = $request->validated();

        return Safe::run(
            function () use ($contactos_alumno, $validated) {
                return DB::transaction(function () use ($contactos_alumno, $validated) {
                    $contactos_alumno->update($validated);
                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'contactos-alumnos.index', 'Contacto actualizado correctamente.');
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
                    ContactosAlumno::find($id)->delete();
                    return true;
                });
            },
            function () {
                return Redirect::route('contactos-alumnos.index')
                    ->with('success', 'ContactosAlumno deleted successfully');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar el contacto. Folio: '.$folio);
            }
        );
    }
}

