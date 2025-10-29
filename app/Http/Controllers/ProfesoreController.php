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

class ProfesoreController extends Controller
{
    // ... Todos tus métodos existentes (index, create, store, etc.) ...
    
    // El resto de tu código para index, create, store, show, edit, update, destroy...
    // ...

    // --- MÉTODO CORREGIDO PARA LA CONSULTA AJAX DE REPORTES ---

    /**
     * Obtiene una lista de profesores filtrada por el ID del área para la petición AJAX.
     * * @param int $area_id El ID del área, pasado como parámetro de ruta.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfesoresPorArea($area_id) // <-- CORRECCIÓN: Ahora recibe $area_id como parámetro de ruta
    {
        // El ID es pasado directamente en el argumento, así que no necesitamos el Request::get()
        if (empty($area_id)) {
            return response()->json([]);
        }

        try {
            $profesores = Profesore::where('fk_area', $area_id) // USANDO LA COLUMNA REAL 'fk_area'
                ->select(
                    'id_profesor as id',        // Alias 'id' es esencial para tu JS
                    'nombre',
                    'apellido_pat',             // Usamos 'apellido_pat' y 'apellido_mat' como en tu DB
                    'apellido_mat'              // Tu JS espera estas tres columnas
                )
                ->orderBy('apellido_pat', 'asc')
                ->orderBy('apellido_mat', 'asc')
                ->get();
            
            // Eliminamos la función 'map' para dejar que el JavaScript ensamble el nombre,
            // ya que el JS de tu vista *ya* está esperando 'nombre', 'apellido_paterno', 'apellido_materno'.
            // Simplemente mapeamos tus columnas a los nombres que espera el JS.

            $profesoresFormateados = $profesores->map(function ($profesor) {
                return [
                    'id' => $profesor->id,
                    'nombre' => $profesor->nombre,
                    // CORRECCIÓN: El JS espera 'apellido_paterno' y 'apellido_materno'
                    'apellido_paterno' => $profesor->apellido_pat,
                    'apellido_materno' => $profesor->apellido_mat,
                ];
            });

            return response()->json($profesoresFormateados); // Retornamos el JSON.

        } catch (\Exception $e) {
            \Log::error('Error AJAX al cargar profesores por área: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al consultar profesores.'], 500);
        }
    }
}