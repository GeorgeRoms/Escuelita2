<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlumnoRequest extends FormRequest
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
            'nombre'        => ['required','string','max:255'],
            'apellido_pat'  => ['required','string','max:255'],
            'apellido_mat'  => ['nullable','string','max:255'],
            'genero'        => ['required','in:M,F'],
            'fk_carrera'    => ['required','exists:carreras,id_carrera'],
            'anio'          => ['required','integer','between:2000,2100'],
            'periodo'       => ['required','integer','in:1,2,3'],
            // no_control NO se valida porque lo generas en el modelo
        ];
    }

    // (Opcional) mensajes en español
     public function messages(): array
     {
         return [
             'fk_carrera.exists' => 'La carrera seleccionada no existe.',
             'genero.in'         => 'El género debe ser M, F u O.',
             'periodo.in'        => 'Periodo debe ser 1 (Ene-Jun), 2 (Ago-Dic) o 3 (Verano).',
         ];
     }
}
