<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\AlumnoCarrera;
use Illuminate\Http\Request;
use App\Http\Requests\AlumnoCarreraRequest;
use Illuminate\Support\Facades\DB;
use App\Support\Safe;
use App\Support\Responder;

class AlumnoCarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Safe::run(
            function () use ($request) {
                $alumnoCarreras = AlumnoCarrera::with(['alumno','carrera'])
                    ->orderByDesc('id')
                    ->paginate(15);

                // devolvemos un array para usarlo en onOk
                return [$alumnoCarreras, $request];
            },
            function ($payload) {
                [$alumnoCarreras, $request] = $payload;

                return view('alumno-carrera.index', compact('alumnoCarreras'))
                    ->with('i', ($request->input('page', 1) - 1) * $alumnoCarreras->perPage());
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
                [$alumnos, $carreras] = $this->catalogos();

                return [
                    'alumnos'        => $alumnos,
                    'carreras'       => $carreras,
                    'alumnoCarrera'  => new AlumnoCarrera(),
                ];
            },
            function ($data) {
                // vista genérica (el alumno es seleccionable)
                return view('alumno-carrera.create', $data);
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
    public function store(AlumnoCarreraRequest $request)
    {
        $data = $request->validated();

        return Safe::run(
            function () use ($data) {
                return DB::transaction(function () use ($data) {
                    // ➊ Restaurar si el alumno está soft-deleted
                    $al = Alumno::withTrashed()
                        ->where('no_control', $data['alumno_no_control'])
                        ->first();
                    if ($al && $al->trashed()) {
                        $al->restore();
                    }

                    // ➋ cerrar activa distinta y crear la nueva
                    $activa = AlumnoCarrera::where('alumno_no_control', $data['alumno_no_control'])
                        ->where('estatus', 'Activo')
                        ->latest('fecha_inicio')
                        ->first();

                    if ($activa && (int)$activa->carrera_id !== (int)$data['carrera_id']) {
                        $activa->update(['estatus' => 'Baja', 'fecha_fin' => now()->toDateString()]);
                    }

                    $esActivo = ($data['estatus'] ?? 'Activo') === 'Activo';

                    return AlumnoCarrera::create([
                        'alumno_no_control' => $data['alumno_no_control'],
                        'carrera_id'        => $data['carrera_id'],
                        'estatus'           => $data['estatus'] ?? 'Activo',
                        'fecha_inicio'      => $data['fecha_inicio'] ?? now()->toDateString(),
                        'fecha_fin'         => $esActivo ? null : ($data['fecha_fin'] ?? now()->toDateString()),
                    ]);
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'alumno-carreras.index', 'Asignación creada.', null, 201);
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(AlumnoCarrera $alumno_carrerum)
    {
        return Safe::run(
            function () use ($alumno_carrerum) {
                $alumnoCarrera = $alumno_carrerum->load(['alumno','carrera']);
                return $alumnoCarrera;
            },
            function ($alumnoCarrera) {
                return view('alumno-carrera.show', compact('alumnoCarrera'));
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
    public function edit(AlumnoCarrera $alumno_carrerum)
    {
        return Safe::run(
            function () use ($alumno_carrerum) {
                $alumnoCarrera = $alumno_carrerum->load(['alumno','carrera']);
                $carreras = Carrera::orderBy('nombre_carr')->pluck('nombre_carr','id_carrera');
                return compact('alumnoCarrera','carreras');
            },
            function ($data) {
                return view('alumno-carrera.edit', $data);
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
    public function update(AlumnoCarreraRequest $request, AlumnoCarrera $alumno_carrerum)
    {
        $alumnoCarrera = $alumno_carrerum;
        $data = $request->validated();

        // Normalización de fechas que ya agregaste…
        if (($data['estatus'] ?? 'Activo') === 'Activo') {
            $data['fecha_fin'] = null;
            $data['fecha_inicio'] = $data['fecha_inicio'] ?? now()->toDateString();
        } else {
            $data['fecha_fin'] = $data['fecha_fin'] ?? now()->toDateString();
        }

        return Safe::run(
            function () use ($alumnoCarrera, $data) {
                return DB::transaction(function () use ($alumnoCarrera, $data) {
                    // ➊ restaurar alumno si está soft-deleted
                    $al = Alumno::withTrashed()
                        ->where('no_control', $data['alumno_no_control'])
                        ->first();
                    if ($al && $al->trashed()) {
                        $al->restore();
                    }

                    // ➋ tu lógica actual de cambio de carrera / update
                    if ((int)$alumnoCarrera->carrera_id !== (int)$data['carrera_id']) {
                        $alumnoCarrera->update(['estatus'=>'Baja','fecha_fin'=>now()->toDateString()]);
                        AlumnoCarrera::create([
                            'alumno_no_control' => $data['alumno_no_control'],
                            'carrera_id'        => $data['carrera_id'],
                            'estatus'           => 'Activo',
                            'fecha_inicio'      => $data['fecha_inicio'] ?? now()->toDateString(),
                            'fecha_fin'         => null,
                        ]);
                    } else {
                        $alumnoCarrera->update($data);
                    }

                    return true;
                });
            },
            function () use ($request) {
                return Responder::ok($request, 'alumno-carreras.index', 'Asignación actualizada.');
            },
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'error.general');
            }
        );
    }

    public function destroy(AlumnoCarrera $alumno_carrerum)
    {
        return Safe::run(
            function () use ($alumno_carrerum) {
                return DB::transaction(function () use ($alumno_carrerum) {
                    // En lugar de borrar, marcamos BAJA para preservar historial
                    $alumno_carrerum->update([
                        'estatus'   => 'Baja',
                        'fecha_fin' => now()->toDateString(),
                    ]);
                    return true;
                });
            },
            function () {
                return redirect()->route('alumno-carreras.index')
                    ->with('success', 'Asignación marcada como Baja.');
            },
            function ($folio) {
                return redirect()->route('error.general')
                    ->with('mensaje', 'No se pudo dar de baja la asignación. Folio: '.$folio);
            }
        );
    }
}

