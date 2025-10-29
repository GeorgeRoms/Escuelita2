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

// La clase se mantiene en singular (ProfesoreController) para coincidir con tus rutas.
class ProfesoreController extends Controller 
{
    /**
     * Muestra una lista de los recursos (profesores).
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // CORRECCIÓN: Usamos paginate() en lugar de all() para obtener una colección paginada.
        // Esto resuelve el error "onFirstPage does not exist".
        $profesores = Profesore::paginate(10); 
        
        // Retornar la vista Blade. Usamos 'profesore.index' (singular) 
        // para coincidir con el nombre de tu carpeta de vistas (resources/views/profesore)
        return view('profesore.index', compact('profesores'));
    }

    // AÑADE AQUÍ TUS OTROS MÉTODOS CRUD (create, store, show, edit, update, destroy)

    /**
     * Obtiene una lista de profesores filtrada por el ID del área para la petición AJAX.
     * * @param int $area_id El ID del área, pasado como parámetro de ruta.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProfesoresPorArea($area_id)
    {
        // Verificación simple: si el ID está vacío, retornamos un arreglo vacío inmediatamente.
        if (empty($area_id)) {
            return response()->json([]);
        }

        try {
            $profesores = Profesore::where('fk_area', $area_id) 
                ->select(
                    'id_profesor', 
                    'nombre',
                    'apellido_pat', 
                    'apellido_mat' 
                )
                ->orderBy('apellido_pat', 'asc')
                ->orderBy('apellido_mat', 'asc')
                ->get();
            
            // Mapeamos los resultados para que el JS de reportes pueda usarlos
            $profesoresFormateados = $profesores->map(function ($profesor) {
                return [
                    'id' => $profesor->id_profesor, 
                    'nombre' => $profesor->nombre,
                    'apellido_paterno' => $profesor->apellido_pat,
                    'apellido_materno' => $profesor->apellido_mat,
                ];
            });

            return response()->json($profesoresFormateados);

        } catch (\Exception $e) {
            \Log::error('Error AJAX al cargar profesores por área: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor al consultar profesores.'], 500);
        }
    }
}
