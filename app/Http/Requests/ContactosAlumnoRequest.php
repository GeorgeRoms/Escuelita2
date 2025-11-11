<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class ContactosAlumnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo'   => $this->correo ? mb_strtolower(trim($this->correo)) : null,
            'telefono' => $this->telefono ? trim($this->telefono) : null,
            'direccion'=> $this->direccion ? trim($this->direccion) : null,
            'fk_alumno'=> $this->fk_alumno ? trim($this->fk_alumno) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $idContacto = $this->route('contactos_alumno')?->id_contacto; // binding por ruta

        // Si ya existe un user vinculado a este alumno, lo ignoramos en la validación de unique(users,email)
        $userIdVinculado = optional(
            User::where('alumno_no_control', $this->input('fk_alumno'))->first()
        )->id;

        return [
            'correo' => [
                'bail','required','email','max:100',
                // Unicidad dentro de contactos_alumnos (ignorando el propio registro en edición)
                Rule::unique('contactos_alumnos', 'correo')->ignore($idContacto, 'id_contacto'),
                // Unicidad cruzada en users.email (ignorando el user ya vinculado a este alumno, si existe)
                Rule::unique('users', 'email')->ignore($userIdVinculado),
            ],
            'telefono' => [
                'required','string','max:20',
                // opcional: valida dígitos, espacios, +, -, ().
                'regex:/^[0-9\-\+\(\)\s]{7,20}$/',
            ],
            'direccion' => ['required','string','max:120'],
            'fk_alumno' => ['required','string','max:24','exists:alumnos,no_control'],
        ];
    }

    public function messages(): array
    {
        return [
            'correo.unique' => 'Este correo ya está registrado (en contactos o en usuarios).',
            'telefono.regex' => 'El teléfono solo admite dígitos y símbolos + - ( ) con 7 a 20 caracteres.',
        ];
    }
}
