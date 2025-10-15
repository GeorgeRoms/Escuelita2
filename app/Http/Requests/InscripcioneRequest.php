<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InscripcioneRequest extends FormRequest
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
            'alumno_no_control'  => ['required','exists:alumnos,no_control'],
            'curso_id'           => ['required','exists:cursos,id_curso'],
            'estado'             => ['required','in:Inscrito,Baja'],
            'intento'            => ['required','in:Normal,Repite,Especial'],
            // 'oportunidad'        => ['nullable','in:1ra,2da'],
            'semestre'           => ['nullable','integer','between:1,12'],
            // 'unidades_reprobadas'=> ['nullable','integer','min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'alumno_no_control.exists' => 'El alumno no existe.',
            'curso_id.exists'          => 'El curso no existe.',
        ];
    }
}
