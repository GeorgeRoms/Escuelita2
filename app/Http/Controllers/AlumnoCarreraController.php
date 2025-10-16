<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\AlumnoCarrera;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\AlumnoCarreraRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AlumnoCarreraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $alumnoCarreras = AlumnoCarrera::with(['alumno','carrera'])
            ->orderByDesc('id')
            ->paginate(15);

        return view('alumno-carrera.index', compact('alumnoCarreras'))
            ->with('i', ($request->input('page', 1) - 1) * $alumnoCarreras->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        [$alumnos, $carreras] = $this->catalogos();

        // vista genérica (el alumno es seleccionable)
        return view('alumno-carrera.create', [
            'alumnos'  => $alumnos,
            'carreras' => $carreras,
            'alumnoCarrera' => new AlumnoCarrera(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AlumnoCarreraRequest $request)
    {
        $data = $request->validated();
        
        return DB::transaction(function () use ($data) {

        // ➊ Restaurar si el alumno está soft-deleted
            $al = Alumno::withTrashed()
                ->where('no_control', $data['alumno_no_control'])
                ->first();
            if ($al && $al->trashed()) {
                $al->restore();
            }

        // ➋ (tu lógica actual) cerrar activa distinta y crear la nueva
            $activa = AlumnoCarrera::where('alumno_no_control', $data['alumno_no_control'])
                ->where('estatus', 'Activo')
                ->latest('fecha_inicio')
                ->first();

            if ($activa && (int)$activa->carrera_id !== (int)$data['carrera_id']) {
                $activa->update(['estatus' => 'Baja', 'fecha_fin' => now()->toDateString()]);
            }

            $esActivo = ($data['estatus'] ?? 'Activo') === 'Activo';

            AlumnoCarrera::create([
                'alumno_no_control' => $data['alumno_no_control'],
                'carrera_id'        => $data['carrera_id'],
                'estatus'           => $data['estatus'] ?? 'Activo',
                'fecha_inicio'      => $data['fecha_inicio'] ?? now()->toDateString(),
                'fecha_fin'         => $esActivo ? null : ($data['fecha_fin'] ?? now()->toDateString()),
            ]);
        });

        return redirect()->route('alumno-carreras.index')->with('success','Asignación creada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AlumnoCarrera $alumno_carrerum)
    {
        $alumnoCarrera = $alumno_carrerum->load(['alumno','carrera']);
        return view('alumno-carrera.show', compact('alumnoCarrera'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AlumnoCarrera $alumno_carrerum)
    {
        $alumnoCarrera = $alumno_carrerum->load(['alumno','carrera']);
        $carreras   = Carrera::orderBy('nombre_carr')->pluck('nombre_carr','id_carrera');

        // Si quieres permitir cambiar alumno aquí, también pasa $alumnos
        return view('alumno-carrera.edit', compact('alumnoCarrera','carreras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AlumnoCarreraRequest $request, AlumnoCarrera $alumno_carrerum)
{
    $alumnoCarrera = $alumno_carrerum;
$data = $request->validated();

// Normalización de fechas que ya agregamos…
if (($data['estatus'] ?? 'Activo') === 'Activo') {
    $data['fecha_fin'] = null;
    $data['fecha_inicio'] = $data['fecha_inicio'] ?? now()->toDateString();
} else {
    $data['fecha_fin'] = $data['fecha_fin'] ?? now()->toDateString();
}

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

        return redirect()->route('alumno-carreras.index')->with('success','Asignación actualizada.');
    });
}

    public function destroy(AlumnoCarrera $alumno_carrerum)
    {
        // En lugar de borrar, marcamos BAJA para preservar historial
        $alumno_carrerum->update([
            'estatus'   => 'Baja',
            'fecha_fin' => now()->toDateString(),
        ]);

        return redirect()->route('alumno-carreras.index')
            ->with('success', 'Asignación marcada como Baja.');
    }
}
