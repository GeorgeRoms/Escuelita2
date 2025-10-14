<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\AlumnoCarrera;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\AlumnoRequest;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Importante: la relación es 'carreras' (belongsToMany), no 'carrera'
        $alumnos = Alumno::with('carreras')->paginate();

        return view('alumno.index', compact('alumnos'))
            ->with('i', ($request->input('page', 1) - 1) * $alumnos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alumno   = new Alumno();
        $carreras = Carrera::orderBy('nombre_carr')->pluck('nombre_carr', 'id_carrera'); // [id => nombre]

        // Para el select, al crear no hay carrera seleccionada
        $carreraActualId = null;

        return view('alumno.create', compact('alumno','carreras','carreraActualId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\App\Http\Requests\AlumnoRequest $request)
    {
        $data = $request->validated();

    DB::transaction(function () use ($data, &$alumno, $request) {
        // Crear alumno (sin carrera_id)
        $alumno = \App\Models\Alumno::create(collect($data)->except('carrera_id')->toArray());

        // Carrera única (si se eligió)
        if (!empty($data['carrera_id'])) {
            // Reemplaza cualquier otra y deja solo ésta
            $alumno->carreras()->sync([$data['carrera_id']]);
        }
    });

    return redirect()->route('alumnos.index')->with('success','Alumno registrado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alumno $alumno)
    {
        $alumno->load('carreras'); // evita N+1
        return view('alumno.show', compact('alumno'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Alumno $alumno)
    {
        $carreras = \App\Models\Carrera::orderBy('nombre_carr')->pluck('nombre_carr', 'id_carrera');

        $alumno->load('carreras');
        $carreraActualId = optional($alumno->carreras->first())->id_carrera;

        return view('alumno.edit', compact('alumno','carreras','carreraActualId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\App\Http\Requests\AlumnoRequest $request, \App\Models\Alumno $alumno)
{
    $data = $request->validated();

    DB::transaction(function () use ($alumno, $data) {
        $alumno->update(collect($data)->except('carrera_id')->toArray());

        if (array_key_exists('carrera_id', $data)) {
            if ($data['carrera_id']) {
                $alumno->carreras()->sync([$data['carrera_id']]); // una sola carrera activa
            } else {
                $alumno->carreras()->detach();
            }
        }
    });

    // ⬇️ redirige al índice (o a show) con flash
    return redirect()->route('alumnos.index')->with('success','Alumno actualizado.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Alumno $alumno): \Illuminate\Http\RedirectResponse
    {
        try {
        DB::transaction(function () use ($alumno) {
            // 1) Marcar BAJA en TODAS las carreras del alumno
            DB::table('alumno_carrera')
                ->where('alumno_no_control', $alumno->no_control)
                ->update([
                    'estatus'   => 'Baja',
                    'fecha_fin' => now()->toDateString(),
                    'updated_at'=> now(),
                ]);

            // 2) Baja lógica del alumno (no rompe FKs)
            $alumno->delete(); // SoftDelete
        });

        return redirect()
            ->route('alumnos.index')
            ->with('success', 'Alumno dado de baja y carreras marcadas como Baja.');
    } catch (\Throwable $e) {
        return redirect()
            ->route('alumnos.index')
            ->withErrors('No se pudo eliminar: '.$e->getMessage());
    }
    }
}

