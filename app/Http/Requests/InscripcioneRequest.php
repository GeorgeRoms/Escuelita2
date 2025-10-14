<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InscripcioneRequest extends FormRequest
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
            'alumno_no_control' => ['required','exists:alumnos,no_control'],
            'curso_id' => ['required','integer','exists:cursos,id_curso',
            \Illuminate\Validation\Rule::unique('inscripciones')->where(fn($q)=>$q
            ->where('alumno_no_control',$this->alumno_no_control)
            ->where('curso_id',$this->curso_id)
            )
        ],
        'fecha' => ['nullable','date'],
        'estatus' => ['nullable','in:Inscrito,Baja'],
        ];
    }
}
