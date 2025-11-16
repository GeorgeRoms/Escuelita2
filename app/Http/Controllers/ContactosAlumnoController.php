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
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

                // 🧱 armamos un payload que ya trae 'direccion' bonita
                $payload = $validated;
                $direccion = $this->armarDireccion($payload);

                if ($direccion !== '') {
                    $payload['direccion'] = $direccion;
                } else {
                    // si por alguna razón quieres mantener lo que venía:
                    $payload['direccion'] = $payload['direccion'] ?? 'N/D';
                }

                // 1) Crea el contacto con todos los campos (incluyendo calle, colonia, etc.)
                $contacto = ContactosAlumno::create($payload);

                // 2) Busca al alumno
                $alumno = Alumno::where('no_control', $payload['fk_alumno'])->first();

                if ($alumno && !empty($payload['correo'])) {
                    $nombre = trim(($alumno->nombre ?? '') . ' ' . ($alumno->apellido_pat ?? '') . ' ' . ($alumno->apellido_mat ?? ''));

                    // 3) Crea/asegura el user
                    $user = User::firstOrCreate(
                        ['email' => $payload['correo']],
                        [
                            'name'              => $nombre ?: ('Alumno ' . $alumno->no_control),
                            'password'          => Hash::make($alumno->no_control), // temporal = no_control
                            'role'              => 'Alumno',
                            'alumno_no_control' => $alumno->no_control,
                        ]
                    );

                    // 4) Completa campos si ya existía (sin tocar password)
                    $changed = false;
                    if ($user->role !== 'Alumno') { $user->role = 'Alumno'; $changed = true; }
                    if (!$user->alumno_no_control) { $user->alumno_no_control = $alumno->no_control; $changed = true; }
                    if (!$user->name && $nombre) { $user->name = $nombre; $changed = true; }
                    if ($changed) $user->save();
                }

                return $contacto;
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

                // 🧱 armamos dirección a partir de campos atomizados
                $payload    = $validated;
                $direccion  = $this->armarDireccion($payload);

                if ($direccion !== '') {
                    $payload['direccion'] = $direccion;
                } else {
                    // si no mandan nada, conservamos la que ya tenía
                    $payload['direccion'] = $contactos_alumno->direccion;
                }

                // 1) Actualiza el contacto
                $contactos_alumno->update($payload);

                // 2) Sincroniza/asegura el user del alumno (igual que ya lo tenías)
                $noCtrl = $contactos_alumno->fk_alumno;
                $correo = $contactos_alumno->correo;

                $alumno = Alumno::where('no_control', $noCtrl)->first();
                if (!$alumno || empty($correo)) {
                    return true; // nada que sincronizar
                }

                // Busca por vínculo o por correo
                $user = User::where('alumno_no_control', $noCtrl)->first();
                if (!$user) {
                    $user = User::where('email', $correo)->first();
                }

                $nombre = trim(($alumno->nombre ?? '') . ' ' . ($alumno->apellido_pat ?? '') . ' ' . ($alumno->apellido_mat ?? ''));

                if (!$user) {
                    $user = User::firstOrCreate(
                        ['email' => $correo],
                        [
                            'name'              => $nombre ?: ('Alumno ' . $noCtrl),
                            'password'          => Hash::make($alumno->no_control), // temporal = no_control
                            'role'              => 'Alumno',
                            'alumno_no_control' => $noCtrl,
                        ]
                    );
                } else {
                    $changed = false;

                    if ($user->role !== 'Alumno') { $user->role = 'Alumno'; $changed = true; }
                    if ($user->alumno_no_control !== $noCtrl) { $user->alumno_no_control = $noCtrl; $changed = true; }
                    if (!$user->name && $nombre) { $user->name = $nombre; $changed = true; }

                    if ($user->email !== $correo) {
                        $existeOtro = User::where('email', $correo)->where('id', '<>', $user->id)->exists();
                        if (!$existeOtro) {
                            $user->email = $correo;
                            $changed = true;
                        }
                    }

                    if ($changed) $user->save();
                }

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

    private function armarDireccion(array $data): string
{
    // helper interno para leer campo como string
    $get = function (string $key) use ($data): string {
        if (!array_key_exists($key, $data) || $data[$key] === null) {
            return '';
        }

        $val = $data[$key];

        // Si llega como array (por name="campo[]" o algo raro), tomamos el primero
        if (is_array($val)) {
            $val = reset($val) ?: '';
        }

        return trim((string) $val);
    };

    $calle   = $get('calle');
    $colonia = $get('colonia');
    $numExt  = $get('num_ext');
    $numInt  = $get('num_int');
    $cp      = $get('cp');
    $estado  = $get('estado');
    $pais    = $get('pais');

    $partes = [];

    if ($calle !== '') {
        $texto = $calle;
        if ($numExt !== '') $texto .= " #{$numExt}";
        if ($numInt !== '') $texto .= " Int. {$numInt}";
        $partes[] = $texto;
    }

    if ($colonia !== '') $partes[] = "Col. {$colonia}";
    if ($cp      !== '') $partes[] = "CP {$cp}";
    if ($estado  !== '') $partes[] = $estado;
    if ($pais    !== '') $partes[] = $pais;

    return implode(', ', $partes);
}


}

