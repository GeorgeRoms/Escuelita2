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
            // 👇 nombres EXACTOS que envía tu form
            'nombre_area' => ['required','string','max:255'],
            'edificio_id' => ['required','integer','exists:edificios,id'],
            'fk_jefe'     => ['nullable','integer','exists:profesores,id_profesor'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre_area.required' => 'El nombre del área es obligatorio.',
            'edificio_id.required' => 'Debes seleccionar un edificio.',
            'edificio_id.exists'   => 'El edificio seleccionado no existe.',
            'fk_jefe.exists'       => 'El/la jefe(a) seleccionado(a) no existe.',
        ];
    }

}
