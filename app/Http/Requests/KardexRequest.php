<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KardexRequest extends FormRequest
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
			'id_kardex' => 'required',
			'fk_alumno' => 'required',
			'fk_curso' => 'required',
			'fecha_inscri' => 'required',
			'estado' => 'required',
			'promedio' => 'required',
			'intento' => 'required',
			'semestre' => 'required',
			'unidades_reprobadas' => 'required',
        ];
    }
}
