<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MateriaRequest extends FormRequest
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
        $id = optional($this->route('materia'))->id_materia;
        
        return [
            'nombre_mat' => ['required','string','max:60'],
            'creditos'   => ['required','integer','in:3,4,5'],
            'fk_cadena'  => ['nullable','integer','exists:materias,id_materia',
                // no te apuntes a ti mismo
                function ($attr, $value, $fail) use ($id) {
                    if ($id && (int)$value === (int)$id) {
                        $fail('La materia no puede ser prerrequisito de sí misma.');
                    }
                },
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($v) {
            $id = optional($this->route('materia'))->id_materia;
            $parent = (int) $this->input('fk_cadena');

            // Chequeo simple de ciclo: subir por la cadena del padre
            // y verificar que no llegamos al propio $id.
            if ($id && $parent) {
                $seen = [];
                while ($parent) {
                    if (in_array($parent, $seen, true)) {
                        $v->errors()->add('fk_cadena', 'Se detectó un ciclo en la cadena de prerrequisitos.');
                    break;
                }
                if ($parent === (int)$id) {
                    $v->errors()->add('fk_cadena', 'No puedes seleccionar una materia descendiente como prerrequisito.');
                    break;
                }
                $seen[] = $parent;
                $parent = (int) \App\Models\Materia::whereKey($parent)->value('fk_cadena');
                }
            }
        });
    }

}
