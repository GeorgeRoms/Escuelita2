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
            'fk_materia' => ['required','exists:materias,id_materia'],
            'fk_profesor'=> ['required','exists:profesores,id_profesor'],
            'aula_id'    => ['nullable','integer','exists:aulas,id'],
            'periodo_id' => ['required','integer','exists:periodos,id'], // <-- recomendado
            'turno'      => ['nullable','in:Matutino,Vespertino,Nocturno'],
            'cupo'       => ['required','integer','min:1'],
            'grupo'      => ['nullable','string','max:10'],

            // NUEVOS CAMPOS
            'dia_semana'  => ['required','in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado'],
            'hora_inicio' => ['required','date_format:H:i'],
            'hora_fin'    => ['required','date_format:H:i','after:hora_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'hora_fin.after' => 'La hora de fin debe ser mayor que la hora de inicio.',
        ];
    }

}
