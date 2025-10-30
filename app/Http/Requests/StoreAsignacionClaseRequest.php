<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAsignacionClaseRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     */
    public function authorize(): bool
    {

        
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     */
    public function rules(): array
    {
        // Define las combinaciones de días válidas, incluyendo las nuevas
        $diasValidos = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado',
            'Lunes-Miércoles-Viernes', 
            'Martes-Jueves-Viernes', 
            'Lunes-Miércoles', 
            'Martes-Jueves'
        ];
        
        return [
            'profesor_id' => [
                'required', 
                'exists:profesores,id_profesor',
            ],
            'materia_id' => [
                'required', 
                'exists:materias,id_materia',
            ],
            'aula_id' => [
                'required', 
                'exists:aulas,id',
            ],
            // CAMPO CORREGIDO: ahora acepta todas las combinaciones
            'dia_semana' => [
                'required', 
                'in:' . implode(',', $diasValidos),
            ],
            'hora_inicio' => [
                'required', 
                'date_format:H:i',
            ],
        ];
    }

    /**
     * Opcional: Define nombres de atributos más legibles para los errores.
     */
    public function attributes(): array
    {
        return [
            'profesor_id' => 'Profesor',
            'materia_id' => 'Materia',
            'aula_id' => 'Aula (Salón)',
            'dia_semana' => 'Día de la Semana',
            'hora_inicio' => 'Hora de Inicio',
        ];
    }
}
