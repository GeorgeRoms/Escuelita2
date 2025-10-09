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
        'nombre_area' => 'required|string|max:60',

        // ANTES (mal):  'exists:edificios,id_edificio'
        // AHORA (bien):
        'fk_edificio' => 'required|integer|exists:edificios,edificio',

        // si 'fk_jefe' apunta a profesores.id_profesor:
        'fk_jefe'     => 'nullable|integer|exists:profesores,id_profesor',
    ];
    }
}
