<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfesoreRequest extends FormRequest
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
    // Detecta si es create o update
    $isUpdate = in_array($this->method(), ['PUT','PATCH'], true);

    // Puede regresar el modelo O solo el ID (string/int)
    $profesorParam = $this->route('profesore'); // model binding o id
    $profesor = $profesorParam instanceof \App\Models\Profesore
        ? $profesorParam
        : null;

    // Para unique de id_profesor
    $profesorId = $profesor instanceof \App\Models\Profesore
        ? $profesor->id_profesor
        : $profesorParam; // si viene como "5" o 5

    // Contacto (solo si tenemos modelo)
    $contacto   = optional($profesor)->contacto;
    $contactoId = optional($contacto)->id_contacto;

    // Correo anterior del contacto (para ignorarlo en unique de users)
    $oldMail = optional($contacto)->correo;

    $noControlRules = ['sometimes','string','max:24'];
    if ($isUpdate && $profesorId) {
        // único, ignorando el actual por columna id_profesor
        $noControlRules[] = Rule::unique('profesores','id_profesor')
                                ->ignore($profesorId, 'id_profesor');
    } else {
        // en create, si lo envías, que sea único
        $noControlRules[] = Rule::unique('profesores','id_profesor');
    }

    return [

        'id_profesor'   => $noControlRules,

        'nombre'       => ['required','string','max:30'],
        'apellido_pat' => ['required','string','max:30'],
        'apellido_mat' => ['nullable','string','max:30'],
        'tipo'         => ['required','string','max:15'],
        'fk_area'      => ['sometimes','nullable','integer','exists:areas,id_area'],

        // Contacto opcional
        'correo' => [
            'nullable','email','max:100',
            Rule::unique('contactos_profesores', 'correo')->ignore($contactoId, 'id_contacto'),
            Rule::unique('users', 'email')->ignore($oldMail, 'email'),
        ],
        'telefono'  => ['nullable','string','max:20'],
        // 🆕 dirección atomizada
        'calle'   => ['nullable','string','max:100'],
        'colonia' => ['nullable','string','max:100'],
        'num_ext' => ['nullable','string','max:10'],
        'num_int' => ['nullable','string','max:10'],
        'cp'      => ['nullable','string','max:10'],
        'estado'  => ['nullable','string','max:60'],
        'pais'    => ['nullable','string','max:60'],
        ];
}

}
