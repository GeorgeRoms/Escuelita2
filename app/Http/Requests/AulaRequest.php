<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AulaRequest extends FormRequest
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
            'edificio_id' => ['required','exists:edificios,id'],
            'salon' => ['required','max:20',
            Rule::unique('aulas')->where(fn($q)=>$q->where('edificio_id',$this->edificio_id))
            ],
        ];
    }
}
