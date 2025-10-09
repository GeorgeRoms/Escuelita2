<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
			'fk_materia'  => ['required','integer','exists:materias,id_materia'],
            'fk_profesor' => ['required','integer','exists:profesores,id_profesor'],
            'fk_edificio' => ['required','integer','exists:edificios,edificio'],
            'cupo'        => ['required','integer','min:1'],
        ];
    }
}
