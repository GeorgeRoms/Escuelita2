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
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
                    $contacto = ContactosProfesore::create($payload);

                    // 2) Busca al profesor
                    $prof = Profesore::where('id_profesor', $validated['fk_profesor'])->first();

                    if ($prof && !empty($validated['correo'])) {
                        $nombre = trim(($prof->nombre ?? '') . ' ' . ($prof->apellido_pat ?? '') . ' ' . ($prof->apellido_mat ?? ''));

                        // 3) Crea/asegura el user (correo del contacto)
                        $user = User::firstOrCreate(
                            ['email' => mb_strtolower(trim($validated['correo']))],
                            [
                                'name'        => $nombre ?: ('Profesor ' . $prof->id_profesor),
                                'password'    => Hash::make('Profesor' . $prof->id_profesor), // temporal
                                'role'        => 'Profesor',
                                'profesor_id' => $prof->id_profesor,
                            ]
                        );

                        // 4) Completa campos si ya existía (sin tocar password)
                        $changed = false;
                        if ($user->role !== 'Profesor')           { $user->role = 'Profesor'; $changed = true; }
                        if ($user->profesor_id !== $prof->id_profesor) { $user->profesor_id = $prof->id_profesor; $changed = true; }
                        if (!$user->name && $nombre)              { $user->name = $nombre; $changed = true; }
                        if ($changed) $user->save();
                    }

                    return $contacto;
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
                    // 🧱 armamos dirección a partir de campos atomizados
                $payload    = $validated;
                $direccion  = $this->armarDireccion($payload);

                if ($direccion !== '') {
                    $payload['direccion'] = $direccion;
                } else {
                    // si no mandan nada, conservamos la que ya tenía
                    $payload['direccion'] = $contactos_profesore->direccion;
                }

                // 1) Actualiza el contacto
                $contactos_profesore->update($payload);

                    // 2) Datos actuales
                    $profId = $contactos_profesore->fk_profesor;
                    $correo = mb_strtolower(trim($contactos_profesore->correo ?? ''));

                    $prof = Profesore::where('id_profesor', $profId)->first();
                    if (!$prof || empty($correo)) {
                        return true; // nada que sincronizar
                    }

                    // 3) Localiza user por vínculo o correo
                    $user = User::where('profesor_id', $profId)->first();
                    if (!$user) {
                        $user = User::where('email', $correo)->first();
                    }

                    $nombre = trim(($prof->nombre ?? '') . ' ' . ($prof->apellido_pat ?? '') . ' ' . ($prof->apellido_mat ?? ''));

                    if (!$user) {
                        // 4) Si no existe, créalo ahora
                        $user = User::firstOrCreate(
                            ['email' => $correo],
                            [
                                'name'        => $nombre ?: ('Profesor ' . $profId),
                                'password'    => Hash::make('Profesor' . $profId), // temporal
                                'role'        => 'Profesor',
                                'profesor_id' => $profId,
                            ]
                        );
                    } else {
                        // 5) Actualiza sin romper password
                        $changed = false;

                        if ($user->role !== 'Profesor')       { $user->role = 'Profesor'; $changed = true; }
                        if ($user->profesor_id !== $profId)    { $user->profesor_id = $profId; $changed = true; }
                        if (!$user->name && $nombre)           { $user->name = $nombre; $changed = true; }

                        // Si el correo cambió, actualiza evitando colisiones
                        if ($user->email !== $correo) {
                            $existeOtro = User::where('email', $correo)->where('id', '<>', $user->id)->exists();
                            if (!$existeOtro) {
                                $user->email = $correo;
                                $changed = true;
                            }
                            // si hay colisión, lo ignoramos silenciosamente (o puedes lanzar warning flash)
                        }

                        if ($changed) $user->save();
                    }

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

