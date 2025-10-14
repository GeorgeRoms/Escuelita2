<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PeriodoRequest extends FormRequest
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
        $id = $this->route('periodo')?->id; // si usas resource con binding

        return [
            'anio'   => ['required','integer','between:2000,2100'],
            'nombre' => [
                'required',
                Rule::in(['Enero-Junio','Agosto-Diciembre']),
                // único por combinación anio+nombre (sólo activos si usas soft deletes)
                Rule::unique('periodos','nombre')
                    ->ignore($id)
                    ->where(fn($q) => $q->where('anio', $this->input('anio'))
                                        ->whereNull('deleted_at')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.unique' => 'Ya existe ese periodo para el año indicado.',
        ];
    }

}
