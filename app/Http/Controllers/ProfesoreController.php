<?php

namespace App\Http\Controllers;

use App\Models\Profesore;
use App\Models\Area; // CORRECCIÓN: Cambiado de CatalArea a Area
use Illuminate\Http\Request;

/**
 * Clase ProfesoreController
 * @package App\Http\Controllers
 */
class ProfesoreController extends Controller
{
    /**
     * Muestra una lista de recursos.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // SOLUCIÓN: Cargar todos los profesores y pasarlos a la vista.
        // Asumo que quieres paginar los resultados. Si no, usa ->get().
        $profesores = Profesore::with('area')->paginate(10); // Cargamos la relación 'area' para mostrar el nombre.

        return view('profesore.index', compact('profesores'));
    }

    /**
     * Muestra el formulario para crear un nuevo recurso.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $profesore = new Profesore();
        
        // Cargar las áreas para el select (usando el modelo Area)
        $catalAreas = Area::all(); // CORRECCIÓN: Llamando al modelo Area

        return view('profesore.create', compact('profesore', 'catalAreas'));
    }

    /**
     * Almacena un recurso recién creado en el almacenamiento.
     * * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Definir reglas de validación
        request()->validate(Profesore::$rules);

        // 2. Obtener todos los datos validados
        $data = $request->all();
        
        // 3. Crear y guardar el nuevo registro de profesor
        try {
            Profesore::create($data);
        } catch (\Exception $e) {
            // Manejo de errores si la creación falla (ej: violación de clave externa, error de longitud)
            return redirect()->route('profesores.create')
                ->with('error', 'Error al guardar el profesor: ' . $e->getMessage())
                ->withInput();
        }

        // 4. Redirigir con mensaje de éxito
        return redirect()->route('profesores.index')
            ->with('success', 'Profesore creado con éxito.');
    }

    /**
     * Muestra el recurso especificado. (Básica)
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $profesore = Profesore::find($id);

        return view('profesore.show', compact('profesore'));
    }

    /**
     * Muestra el formulario para editar el recurso especificado. (Básica)
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $profesore = Profesore::find($id);
        $catalAreas = Area::all(); // CORRECCIÓN: Llamando al modelo Area
        
        return view('profesore.edit', compact('profesore', 'catalAreas'));
    }

    /**
     * Actualiza el recurso especificado en el almacenamiento. (Básica)
     * @param \Illuminate\Http\Request $request
     * @param Profesore $profesore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profesore $profesore)
    {
        request()->validate(Profesore::$rules);

        $profesore->update($request->all());

        return redirect()->route('profesores.index')
            ->with('success', 'Profesore actualizado con éxito');
    }

    /**
     * Elimina el recurso especificado del almacenamiento. (Básica)
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $profesore = Profesore::find($id)->delete();

        return redirect()->route('profesores.index')
            ->with('success', 'Profesore eliminado con éxito');
    }
}
