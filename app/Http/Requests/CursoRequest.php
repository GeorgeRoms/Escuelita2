<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // <-- Importación necesaria para Rule::in()

class CursoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Define las combinaciones de días válidas, incluyendo los días individuales y las combinaciones nuevas.
        $diasValidos = [
            'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', // Días individuales originales
            'Lunes-Miércoles-Viernes', 
            'Martes-Jueves-Viernes', 
            'Lunes-Miércoles', 
            'Martes-Jueves' // Nuevas combinaciones
        ];

        return [
            'fk_materia'  => ['required', 'exists:materias,id_materia'],
            'fk_profesor' => ['required', 'exists:profesores,id_profesor'],
            'aula_id'     => ['nullable', 'integer', 'exists:aulas,id'],
            'periodo_id'  => ['required', 'integer', 'exists:periodos,id'],
            'turno'       => ['nullable', 'in:Matutino,Vespertino,Nocturno'],
            'cupo'        => ['required', 'integer', 'min:1'],
            'grupo'       => ['nullable', 'string', 'max:10'],

            // CAMPO CORREGIDO: utiliza la lista $diasValidos
            'dia_semana' => [
                'required',
                'string', // Ahora es un string largo en la DB, ya no es un ENUM
                'max:30', // Mantengo tu límite de 30 caracteres
                Rule::in($diasValidos) // Usa la lista definida arriba
            ],

            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin'    => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'hora_fin.after' => 'La hora de fin debe ser mayor que la hora de inicio.',
            'dia_semana.in'  => 'El día de la semana seleccionado no es una combinación válida.',
        ];
    }
}
