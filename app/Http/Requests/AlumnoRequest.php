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
			'no_control' => 'required',
			'nombre' => 'required|string',
			'apellido_pat' => 'required|string',
			'apellido_mat' => 'required|string',
			'genero' => 'required|string',
			'fk_carrera' => 'required',
        ];
    }
}
