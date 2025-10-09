<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
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
			'nombre_area' => ['required','string','max:60','unique:areas,nombre_area'],
            'fk_edificio' => ['nullable','integer','exists:edificios,id_edificio'],
            'fk_jefe'     => ['nullable','integer','exists:profesores,id_profesor','unique:areas,fk_jefe'],
        ];
    }
}
