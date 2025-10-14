<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EdificioRequest extends FormRequest
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
        $id = $this->route('edificio')?->id;

    return [
        'codigo' => [
            'required','string','max:10',
            Rule::unique('edificios','codigo')
                ->ignore($id)              // ignora el actual
                ->whereNull('deleted_at'), // sólo compara contra activos
        ],
        'nombre' => ['required','string','max:255'],
    ];
    }
}
