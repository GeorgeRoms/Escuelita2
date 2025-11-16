<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\AlumnoCarrera;
use App\Models\ContactosAlumno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AlumnoRequest;
use App\Support\Safe;
use App\Support\Responder;
use Illuminate\Support\Arr;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $alumnos = Alumno::with('carreras')->paginate();
                return [$alumnos, $request];
            },
            function ($payload) {
                [$alumnos, $request] = $payload;
                return view('alumno.index', compact('alumnos'))
                    ->with('i', ($request->input('page', 1) - 1) * $alumnos->perPage());
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
                $alumno = new Alumno();
                $carreras = Carrera::orderBy('nombre_carr')->pluck('nombre_carr', 'id_carrera');
                $carreraActualId = null;
                return compact('alumno','carreras','carreraActualId');
            },
            function ($data) {
                return view('alumno.create', $data);
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
    public function store(\App\Http\Requests\AlumnoRequest $request)
{
    $data = $request->validated();

    return \App\Support\Safe::run(
        function () use ($data) {
            return DB::transaction(function () use ($data) {
                // Crea Alumno (solo campos propios del alumno)
                /** @var Alumno $alumno */
                $alumno = Alumno::create(collect($data)->except(['correo','telefono','direccion','carrera_id'])->toArray());

                // Carrera (si la manejas desde aquí)
                if (!empty($data['carrera_id'])) {
                    $alumno->carreras()->sync([$data['carrera_id']]);
                }

                // Sincroniza contacto + user (opcional)
                $this->syncContactoYUsuario($alumno, $data);

                return $alumno;
            });
        },
        function () use ($request) {
            return \App\Support\Responder::ok($request, 'alumnos.index', 'Alumno registrado.', null, 201);
        },
        function ($folio) use ($request) {
            return \App\Support\Responder::fail($request, $folio, 'error.general');
        }
    );
}



    /**
     * Display the specified resource.
     */
    public function show(Alumno $alumno)
    {
        return Safe::run(
            function () use ($alumno) {
                $alumno->load('carreras'); // evita N+1
                return $alumno;
            },
            function ($alumno) {
                return view('alumno.show', compact('alumno'));
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
    public function edit(Alumno $alumno)
    {
        return Safe::run(
            function () use ($alumno) {
                $carreras = Carrera::orderBy('nombre_carr')->pluck('nombre_carr', 'id_carrera');
                $alumno->load('carreras');
                $carreraActualId = optional($alumno->carreras->first())->id_carrera;
                return compact('alumno','carreras','carreraActualId');
            },
            function ($data) {
                return view('alumno.edit', $data);
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
    public function update(\App\Http\Requests\AlumnoRequest $request, Alumno $alumno)
{
    $data = $request->validated();

    return \App\Support\Safe::run(
        function () use ($alumno, $data) {
            return DB::transaction(function () use ($alumno, $data) {
                // Actualiza Alumno
                $alumno->update(collect($data)->except(['correo','telefono','direccion','carrera_id'])->toArray());

                // Carrera
                if (array_key_exists('carrera_id', $data)) {
                    if ($data['carrera_id']) {
                        $alumno->carreras()->sync([$data['carrera_id']]);
                    } else {
                        $alumno->carreras()->detach();
                    }
                }

                // Sync contacto + user (UNA sola vez) y regresamos $sync
                $sync = $this->syncContactoYUsuario($alumno, $data /* , deleteUserIfNoContact: true|false */);

                return $sync; // ⬅️ esto viaja al callback de éxito
            });
        },
        function ($sync) use ($request) { // ⬅️ aquí recibes el payload ($sync)
            return \App\Support\Responder::ok(
                $request,
                'alumnos.index',
                ($sync['created_user'] ?? false) && !empty($sync['temp_password'])
                    ? 'Alumno guardado. Usuario creado con contraseña temporal: ' . $sync['temp_password']
                    : 'Alumno guardado.'
            );
        },
        function ($folio) use ($request) {
            return \App\Support\Responder::fail($request, $folio, 'error.general');
        }
    );
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alumno $alumno)
    {
        return Safe::run(
            function () use ($alumno) {
                return DB::transaction(function () use ($alumno) {
                    // 1) Marcar BAJA en TODAS las carreras del alumno
                    DB::table('alumno_carrera')
                        ->where('alumno_no_control', $alumno->no_control)
                        ->update([
                            'estatus'    => 'Baja',
                            'fecha_fin'  => now()->toDateString(),
                            'updated_at' => now(),
                        ]);

                    // 2) Baja lógica del alumno (no rompe FKs)
                    $alumno->delete(); // SoftDelete
                    return true;
                });
            },
            function () {
                return redirect()
                    ->route('alumnos.index')
                    ->with('success', 'Alumno dado de baja y carreras marcadas como Baja.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar. Folio: '.$folio);
            }
        );
    }


    private function nombreCompleto(Alumno $alumno): string
{
    return trim($alumno->nombre.' '.$alumno->apellido_pat.' '.($alumno->apellido_mat ?? ''));
}

/**
 * Sincroniza contacto_alumno y users a partir de los datos recibidos.
 * - Si trae correo/teléfono/dirección => upsert contacto y upsert user (si hay correo).
 * - Si no trae nada de contacto => borra contacto (y opcionalmente usuario).
 */
private function syncContactoYUsuario(Alumno $alumno, array $data, bool $deleteUserIfNoContact = false): array
{
    $correo    = trim($data['correo']    ?? '');
    $telefono  = trim($data['telefono']  ?? '');
    $calle     = trim($data['calle']     ?? '');
    $colonia   = trim($data['colonia']   ?? '');
    $numExt    = trim($data['num_ext']   ?? '');
    $numInt    = trim($data['num_int']   ?? '');
    $cp        = trim($data['cp']        ?? '');
    $estado    = trim($data['estado']    ?? '');
    $pais      = trim($data['pais']      ?? '');

    $direccionCompleta = $this->armarDireccion($calle, $colonia, $numExt, $numInt, $cp, $estado, $pais);

    $contacto  = $alumno->contacto()->first();
    $oldMail   = $contacto?->correo;

    /* =======================
       CONTACTO (hasOne)
       Regla: SOLO creamos/actualizamos contacto si viene correo,
       porque 'correo' es NOT NULL y UNIQUE.
       Si NO viene correo => borramos contacto si existía.
       ======================= */
    if ($correo !== '') {
        if ($contacto) {
            $contacto->update([
                'correo'    => $correo,
                'telefono'  => $telefono !== '' ? $telefono : $contacto->telefono,   // conserva si mandan vacío
                'calle'     => $calle   !== '' ? $calle   : $contacto->calle,
                'colonia'   => $colonia !== '' ? $colonia : $contacto->colonia,
                'num_ext'   => $numExt  !== '' ? $numExt  : $contacto->num_ext,
                'num_int'   => $numInt  !== '' ? $numInt  : $contacto->num_int,
                'cp'        => $cp      !== '' ? $cp      : $contacto->cp,
                'estado'    => $estado  !== '' ? $estado  : $contacto->estado,
                'pais'      => $pais    !== '' ? $pais    : $contacto->pais,
                'direccion' => $direccionCompleta !== '' ? $direccionCompleta : $contacto->direccion,
            ]);
        } else {
            $alumno->contacto()->create([
                'correo'    => $correo,
                'telefono'  => $telefono !== '' ? $telefono : 'N/D',
                'calle'       => $calle   ?: null,
                'colonia'     => $colonia ?: null,
                'num_ext'     => $numExt  ?: null,
                'num_int'     => $numInt  ?: null,
                'cp'          => $cp      ?: null,
                'estado'      => $estado  ?: null,
                'pais'        => $pais    ?: null,
                'direccion'   => $direccionCompleta ?: 'N/D',
                'fk_alumno' => $alumno->no_control,
            ]);
        }
    } else {
        if ($contacto) {
            $contacto->delete();
        }
    }

    /* =======================
       USER (upsert)
       - si viene correo: crea/actualiza User
       - pass al crear = no_control (hasheado)
       - role = 'Alumno'
       - alumno_no_control = no_control del alumno
       - si NO viene correo: opción de borrar user antiguo si así lo decides
       ======================= */
    $createdUser  = false;
    $tempPassword = null;

    if ($correo !== '') {
        // Buscar por nuevo correo; si cambió, intenta migrar desde el anterior
        $user = User::where('email', $correo)->first();
        if (!$user && $oldMail && $oldMail !== $correo) {
            $user = User::where('email', $oldMail)->first();
        }

        $nombreCompleto = trim($alumno->nombre.' '.$alumno->apellido_pat.' '.($alumno->apellido_mat ?? ''));

        if ($user) {
            $user->forceFill([
                'name'              => $nombreCompleto,
                'email'             => $correo,
                'alumno_no_control' => $alumno->no_control,
            ])->save();

            $this->ensureAlumnoRole($user);
        } else {
            $tempPassword = $alumno->no_control; // para mostrar en flash si quieres
            $user = User::create([
                'name'              => $nombreCompleto,
                'email'             => $correo,
                'alumno_no_control' => $alumno->no_control,
                'password'          => \Illuminate\Support\Facades\Hash::make($alumno->no_control),
            ]);
            $createdUser = true;

            $this->ensureAlumnoRole($user);
        }
    } else {
        if ($oldMail && $deleteUserIfNoContact) {
            User::where('email', $oldMail)->delete();
        }
    }

    return ['created_user' => $createdUser, 'temp_password' => $tempPassword];
}

private function ensureAlumnoRole(User $user): void
{
    // Si usas Spatie, puedes dejar el bloque de antes. Aquí el plan B por columna:
    if (Schema::hasColumn('users', 'role')) {
        if ($user->role !== 'Alumno') {       // <-- mayúscula exacta del ENUM
            $user->forceFill(['role' => 'Alumno'])->save();
        }
    }
}

private function armarDireccion(
    ?string $calle,
    ?string $colonia,
    ?string $numExt,
    ?string $numInt,
    ?string $cp,
    ?string $estado,
    ?string $pais
): string {
    $partes = [];

    if ($calle) {
        $texto = $calle;
        if ($numExt) $texto .= " #{$numExt}";
        if ($numInt) $texto .= " Int. {$numInt}";
        $partes[] = $texto;
    }

    if ($colonia) $partes[] = "Col. {$colonia}";
    if ($cp)      $partes[] = "CP {$cp}";
    if ($estado)  $partes[] = $estado;
    if ($pais)    $partes[] = $pais;

    return implode(', ', $partes);
}

}


