<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlumnoCarreraRequest extends FormRequest
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
        return [
            // cuando se usa create “genérico” sí validamos el alumno
            'alumno_no_control' => ['required','exists:alumnos,no_control'],
            'carrera_id'        => ['required','exists:carreras,id_carrera'],
            'estatus'           => ['required','in:Activo,Baja'],
            'fecha_inicio'      => ['nullable','date'],
            'fecha_fin'         => ['nullable','date','after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'alumno_no_control.required' => 'Selecciona el alumno.',
            'alumno_no_control.exists'   => 'El alumno no existe.',
            'carrera_id.required'        => 'Selecciona la carrera.',
            'carrera_id.exists'          => 'La carrera no existe.',
        ];
    }
}
