<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\AlumnoCarrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AlumnoRequest;
use App\Support\Safe;
use App\Support\Responder;
use Illuminate\Support\Arr;

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
    public function store(AlumnoRequest $request)
{
    $data = $request->validated();

    // 🔒 Solo los campos permitidos (no pasan no_control/consecutivo)
    $solo = Arr::only($data, [
        'nombre','apellido_pat','apellido_mat','genero',
        'anio','periodo','carrera_id', 'semestre'
    ]);

    return Safe::run(
        function () use ($solo) {
            return DB::transaction(function () use ($solo) {

                // crea alumno sin carrera_id
                $alumno = Alumno::create(Arr::except($solo, ['carrera_id']));

                if (!empty($solo['carrera_id'])) {
                    $alumno->carreras()->sync([$solo['carrera_id']]);
                }
                return $alumno;
            });
        },
        function () use ($request) {
            return Responder::ok($request, 'alumnos.index', 'Alumno registrado.', null, 201);
        },
        function ($folio) use ($request) {
            return Responder::fail($request, $folio, 'error.general');
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
    public function update(AlumnoRequest $request, Alumno $alumno)
{
    $data = $request->validated();

    $solo = Arr::only($data, [
        'nombre','apellido_pat','apellido_mat','genero',
        'anio','periodo','carrera_id', 'semestre'
    ]);

    return Safe::run(
        function () use ($alumno, $solo) {
            return DB::transaction(function () use ($alumno, $solo) {

                $alumno->update(Arr::except($solo, ['carrera_id']));

                if (array_key_exists('carrera_id', $solo)) {
                    if ($solo['carrera_id']) {
                        $alumno->carreras()->sync([$solo['carrera_id']]);
                    } else {
                        $alumno->carreras()->detach();
                    }
                }
                return true;
            });
        },
        function () use ($request) {
            return Responder::ok($request, 'alumnos.index', 'Alumno actualizado.');
        },
        function ($folio) use ($request) {
            return Responder::fail($request, $folio, 'error.general');
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
}


