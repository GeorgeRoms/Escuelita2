<?php

namespace App\Http\Controllers;

use App\Models\Profesore;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ProfesoreRequest;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;
use App\Models\ContactosProfesore;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfesoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                // Se corrigió el error de sintaxis eliminando caracteres invisibles.
                $profesores = Profesore::with('area') // para mostrar nombre de área sin N+1
                    ->orderBy('nombre')
                    ->paginate();

                return [$profesores, $request];
            },
            function ($payload) {
                [$profesores, $request] = $payload;

                return view('profesore.index', compact('profesores'))
                    ->with('i', ($request->input('page', 1) - 1) * $profesores->perPage());
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
                $profesore = new Profesore();
                // CORRECCIÓN: Se usa get() para devolver objetos (necesario para $area->id_area en la vista)
                $catalAreas = Area::orderBy('nombre_area')->get();

                return compact('profesore', 'catalAreas');
            },
            function ($data) {
                return view('profesore.create', $data);
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
    public function store(ProfesoreRequest $request)
{
    $data = $request->validated();

    return Safe::run(
        function () use ($data) {
            return DB::transaction(function () use ($data) {

                // solo campos del profesor
                $payload = collect($data)
                    ->only(['id_profesor','nombre','apellido_pat','apellido_mat','tipo','fk_area'])
                    ->toArray();

                // por si apellido_mat viene null pero en BD es NOT NULL
                if (!array_key_exists('apellido_mat', $payload) || is_null($payload['apellido_mat'])) {
                    $payload['apellido_mat'] = '';
                }

                $profesor = new Profesore();
                $profesor->forceFill($payload)->save();

                $sync = $this->syncContactoYUsuarioProfesor($profesor, $data);

                return $sync;
            });
        },
        function ($sync) use ($request) {
            return Responder::ok(
                $request,
                'profesores.index',
                ($sync['created_user'] ?? false) && !empty($sync['temp_password'])
                    ? 'Profesor guardado. Usuario creado. Contraseña temporal: Profesor'.$sync['temp_password']
                    : 'Profesor guardado.'
            );
        },
        function ($folio) use ($request) {
            return Responder::fail($request, $folio, 'error.general');
        }
    );
}


    /**
     * Display the specified resource.
     */
    public function show(Profesore $profesore)
    {
        return Safe::run(
            function () use ($profesore) {
                $profesore->load('area');
                return compact('profesore');
            },
            function ($data) {
                return view('profesore.show', $data);
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
    public function edit(Profesore $profesore)
    {
        return Safe::run(
            function () use ($profesore) {
                // CORRECCIÓN: Se usa get() para devolver objetos (necesario para $area->id_area en la vista)
                $catalAreas = Area::orderBy('nombre_area')->get();
                $profesore->load('contacto','area');
                return compact('profesore', 'catalAreas');
            },
            function ($data) {
                return view('profesore.edit', $data);
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
    public function update(ProfesoreRequest $request, Profesore $profesore)
{
    $data = $request->validated();

    return Safe::run(
        function () use ($profesore, $data) {
            return DB::transaction(function () use ($profesore, $data) {

                $payload = collect($data)
                    ->only(['id_profesor','nombre','apellido_pat','apellido_mat','tipo','fk_area'])
                    ->toArray();

                if (!array_key_exists('apellido_mat', $payload) || is_null($payload['apellido_mat'])) {
                    $payload['apellido_mat'] = '';
                }

                $profesore->forceFill($payload)->save();

                $sync = $this->syncContactoYUsuarioProfesor($profesore, $data);
                return $sync;
            });
        },
        function ($sync) use ($request) {
            return Responder::ok(
                $request,
                'profesores.index',
                ($sync['created_user'] ?? false) && !empty($sync['temp_password'])
                    ? 'Profesor actualizado. Usuario creado. Contraseña temporal: Profesor'.$sync['temp_password']
                    : 'Profesor actualizado.'
            );
        },
        function ($folio) use ($request) {
            return Responder::fail($request, $folio, 'error.general');
        }
    );
}


    public function destroy(Profesore $profesore)
    {
        return Safe::run(
            function () use ($profesore) {
                return DB::transaction(function () use ($profesore) {
                    $profesore->delete();
                    return true;
                });
            },
            function () {
                return redirect()->route('profesores.index')
                    ->with('success', 'Profesor eliminado.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo eliminar el profesor. Folio: '.$folio);
            }
        );
    }


    private function nombreCompletoProfesor(Profesore $p): string
{
    return trim($p->nombre.' '.$p->apellido_pat.' '.($p->apellido_mat ?? ''));
}

private function ensureProfesorRole(User $user): void
{
    // Si usas Spatie
    if (method_exists($user, 'syncRoles')) {
        try {
            $user->syncRoles(['Profesor']);
            return;
        } catch (\Throwable $e) {
            // cae al plan B
        }
    }

    // Plan B con la columna role ENUM('Administrador','Administrativo','Alumno','Profesor')
    if (Schema::hasColumn('users', 'role') && $user->role !== 'Profesor') {
        $user->forceFill(['role' => 'Profesor'])->save();
    }
}

/**
 * Sincroniza contacto_profesor + users.
 * - Solo crea/actualiza contacto si hay correo (en tu BD correo es NOT NULL).
 * - Crea/actualiza user con role Profesor.
 * - Password inicial (cuando se crea) = id_profesor.
 */
private function syncContactoYUsuarioProfesor(Profesore $profesor, array $data, bool $deleteUserIfNoContact = false): array
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

    $contacto = $profesor->contacto()->first();
    $oldMail  = $contacto?->correo;

    // ===== CONTACTO =====
    if ($correo !== '') {
        if ($contacto) {
            $contacto->update([
                'correo'    => $correo,
                'telefono'  => $telefono !== '' ? $telefono : $contacto->telefono,
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
            $profesor->contacto()->create([
                'correo'      => $correo,
                'telefono'    => $telefono !== '' ? $telefono : 'N/D',
                'calle'       => $calle   ?: null,
                'colonia'     => $colonia ?: null,
                'num_ext'     => $numExt  ?: null,
                'num_int'     => $numInt  ?: null,
                'cp'          => $cp      ?: null,
                'estado'      => $estado  ?: null,
                'pais'        => $pais    ?: null,
                'direccion'   => $direccionCompleta ?: 'N/D',
                'fk_profesor' => $profesor->id_profesor,
            ]);
        }
    } else {
        if ($contacto) {
            $contacto->delete();
        }
    }

    // ===== USER =====
    $createdUser  = false;
    $tempPassword = null;

    if ($correo !== '') {
        // busca por nuevo mail; si cambió, intenta migrar por el viejo
        $user = User::where('email', $correo)->first();
        if (!$user && $oldMail && $oldMail !== $correo) {
            $user = User::where('email', $oldMail)->first();
        }

        $name = $this->nombreCompletoProfesor($profesor);

        if ($user) {
            $payload = [
                'name'  => $name,
                'email' => $correo,
            ];

            if (Schema::hasColumn('users', 'profesor_id')) {
                $payload['profesor_id'] = $profesor->id_profesor;
            }

            $user->forceFill($payload)->save();
            $this->ensureProfesorRole($user);
        } else {
            $tempPassword = (string) $profesor->id_profesor;

            $payload = [
                'name'     => $name,
                'email'    => $correo,
                'password' => Hash::make('Profesor'.$tempPassword),
            ];

            if (Schema::hasColumn('users', 'profesor_id')) {
                $payload['profesor_id'] = $profesor->id_profesor;
            }

            $user = User::create($payload);
            $createdUser = true;

            $this->ensureProfesorRole($user);
        }
    } else {
        if ($oldMail && $deleteUserIfNoContact) {
            User::where('email', $oldMail)->delete();
        }
    }

    return ['created_user' => $createdUser, 'temp_password' => $tempPassword];
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
