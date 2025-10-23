<?php

namespace App\Http\Controllers;

use App\Models\AsignacionClase;
use App\Models\Profesore;
use App\Models\Materia;
use App\Models\Aula;
use Illuminate\Http\Request;
use App\Support\Safe;
use App\Support\Responder;
use Carbon\Carbon;
use App\Http\Requests\StoreAsignacionClaseRequest; // <-- ¡NUEVO! Importamos el Form Request

/**
 * Controlador para la gestión de Asignaciones de Clases (Horarios).
 */
class AsignacionClaseController extends Controller
{
    /**
     * Genera un array de horarios de inicio posibles para clases de 2 horas.
     * El formato es clave (HH:MM) => valor (HH:MM - HH:MM).
     */
    private function generarHorariosDisponibles()
    {
        $horarios = [];
        // Hora de inicio del rango (07:00 AM)
        $horaActual = Carbon::createFromTime(7, 0, 0); 
        // Hora límite (la hora de inicio debe permitir una clase de 2 horas)
        $horaLimite = Carbon::createFromTime(17, 0, 0); 

        while ($horaActual->lte($horaLimite)) {
            $horaFin = $horaActual->copy()->addHours(2); // Duración de la clase: 2 horas
            
            $key = $horaActual->format('H:i');
            $value = $horaActual->format('H:i') . ' - ' . $horaFin->format('H:i');
            
            $horarios[$key] = $value;
            
            // Avanzar a la siguiente hora de inicio (bloques de 2 horas)
            $horaActual->addHours(2); 
        }

        return $horarios;
    }

    /**
     * Muestra una lista de las asignaciones de clases.
     */
    public function index(Request $request)
    {
        // 1. Obtener datos principales
        $asignaciones = AsignacionClase::with(['profesor', 'materia', 'aula'])
            ->paginate(10); 
            
        // 2. Obtener datos para filtros o selectores en la vista
        $profesores = Profesore::all();
        $materias = Materia::all();
        $aulas = Aula::all();
        $horariosDisponibles = $this->generarHorariosDisponibles(); 

        // 3. Devolver la vista con los datos
        return view('asignaciones.index', compact('asignaciones', 'profesores', 'materias', 'aulas', 'horariosDisponibles'));
    }

    /**
     * Muestra el formulario para crear una nueva asignación de clase.
     */
    public function create()
    {
        $profesores = Profesore::all(); 
        $materias = Materia::all();
        $aulas = Aula::all();
        $horariosDisponibles = $this->generarHorariosDisponibles(); 

        return view('asignaciones.create', compact('profesores', 'materias', 'aulas', 'horariosDisponibles'));
    }

    /**
     * Almacena una nueva asignación de clase en la base de datos.
     *
     * @param  \App\Http\Requests\StoreAsignacionClaseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAsignacionClaseRequest $request) // <--- Usamos el nuevo Request
    {
        // El método Safe::run será ejecutado SOLO si la validación en el Request pasa.
        return Safe::run(
            
            // 1. $action: La lógica a "intentar".
            function () use ($request) {
                
                // Los datos validados ya están disponibles (sin hora_fin).
                $validated = $request->validated(); 

                // ** Lógica para calcular hora_fin **
                // 1. Crear un objeto Carbon a partir de la hora de inicio.
                $horaInicio = Carbon::createFromFormat('H:i', $validated['hora_inicio']);
                
                // 2. Calcular la hora de fin (2 horas después) y formatearla a HH:MM.
                $horaFin = $horaInicio->copy()->addHours(2)->format('H:i');
                
                // 3. Añadir el campo calculado al array de datos para guardado masivo.
                $validated['hora_fin'] = $horaFin;
                
                // Retorna el resultado de la acción (la nueva asignación)
                return AsignacionClase::create($validated);
            },
            
            // 2. $onOk: Se ejecuta si $action tiene éxito.
            function ($asignacion) use ($request) {
                return Responder::ok($request, 'asignaciones.index', 'Asignación creada con éxito.');
            },
            
            // 3. $onFail: Se ejecuta si $action falla (por ejemplo, un error del TRIGGER de la BD).
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'asignaciones.index', 'Error al crear la asignación.');
            }
        );
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(AsignacionClase $asignacion)
    {
        $profesores = Profesore::all(); 
        $materias = Materia::all();
        $aulas = Aula::all();
        $horariosDisponibles = $this->generarHorariosDisponibles(); 

        return view('asignaciones.edit', compact('asignacion', 'profesores', 'materias', 'aulas', 'horariosDisponibles'));
    }

    /**
     * Actualiza la asignación de clase especificada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AsignacionClase  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AsignacionClase $asignacion)
    {
        // CORRECCIÓN APLICADA:
        return Safe::run(
            
            // 1. $action
            function () use ($request, $asignacion) {
                $validated = $request->validate([
                    'profesor_id' => 'required|exists:profesores,id_profesor',
                    'materia_id' => 'required|exists:materias,id_materia',
                    'aula_id' => 'required|exists:aulas,id',
                    'dia_semana' => 'required|in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
                    'hora_inicio' => 'required|date_format:H:i',
                    'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
                ]);

                return $asignacion->update($validated);
            },
            
            // 2. $onOk
            function ($result) use ($request) { // $result = true/false del update
                return Responder::ok($request, 'asignaciones.index', 'Asignación actualizada con éxito.');
            },
            
            // 3. $onFail
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'asignaciones.index', 'Error al actualizar la asignación.');
            }
        );
    }

    /**
     * Elimina la asignación de clase especificada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AsignacionClase  $asignacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, AsignacionClase $asignacion)
    {
        // CORRECCIÓN APLICADA:
        return Safe::run(
            
            // 1. $action
            function () use ($asignacion) {
                return $asignacion->delete();
            },
            
            // 2. $onOk
            function ($result) use ($request) { // $result = true/false del delete
                return Responder::ok($request, 'asignaciones.index', 'Asignación eliminada con éxito.');
            },
            
            // 3. $onFail
            function ($folio) use ($request) {
                return Responder::fail($request, $folio, 'asignaciones.index', 'Error al eliminar la asignación.');
            }
        );
    }
}