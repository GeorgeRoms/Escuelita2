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
        // Si tienes un sistema de permisos (por ejemplo, con Spatie),
        // aquí verificarías si el usuario tiene permiso para 'crear asignaciones'.
        // Por ahora, lo ponemos en true para que la validación continúe.
        return true; 
    }

    /**
     * Obtiene las reglas de validación que se aplican a la petición.
     */
    public function rules(): array
    {
        // Incluimos solo los campos que vienen del formulario (sin 'hora_fin')
        // El campo 'hora_fin' lo calcularemos en el controlador.
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
            'dia_semana' => [
                'required', 
                'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
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