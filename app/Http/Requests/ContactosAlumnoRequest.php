<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

class ContactosAlumnoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo'    => $this->correo    ? mb_strtolower(trim($this->correo))    : null,
            'telefono'  => $this->telefono  ? trim($this->telefono)                 : null,
            'calle'     => $this->calle     ? trim($this->calle)                    : null,
            'colonia'   => $this->colonia   ? trim($this->colonia)                  : null,
            'num_ext'   => $this->num_ext   ? trim($this->num_ext)                  : null,
            'num_int'   => $this->num_int   ? trim($this->num_int)                  : null,
            'cp'        => $this->cp        ? trim($this->cp)                       : null,
            'estado'    => $this->estado    ? trim($this->estado)                   : null,
            'pais'      => $this->pais      ? trim($this->pais)                     : null,
            'fk_alumno' => $this->fk_alumno ? trim($this->fk_alumno)                : null,
        ]);
    }

    public function rules(): array
    {
        $contacto   = $this->route('contactos_alumno');
        $idContacto = $contacto?->id_contacto;
        $oldMail    = $contacto?->correo;

        $userIdVinculado = optional(
            User::where('alumno_no_control', $this->input('fk_alumno'))->first()
        )->id;

        return [
            'correo' => [
                'bail','required','email','max:100',
                Rule::unique('contactos_alumnos', 'correo')->ignore($idContacto, 'id_contacto'),
                Rule::unique('users', 'email')->ignore($userIdVinculado),
            ],

            'telefono' => [
                'required','string','max:20',
                'regex:/^[0-9\-\+\(\)\s]{7,20}$/',
            ],

            // Dirección atomizada (ya SIN string en prepareForValidation)
            'calle'   => 'nullable|max:100',
            'colonia' => 'nullable|max:100',
            'num_ext' => 'nullable|max:10',
            'num_int' => 'nullable|max:10',
            'cp'      => 'nullable|max:10',
            'estado'  => 'nullable|max:60',
            'pais'    => 'nullable|max:60',

            'fk_alumno' => 'required|string|max:24|exists:alumnos,no_control',
        ];
    }

    public function messages(): array
    {
        return [
            'correo.unique'   => 'Este correo ya está registrado (en contactos o en usuarios).',
            'telefono.regex'  => 'El teléfono solo admite dígitos y símbolos + - ( ) con 7 a 20 caracteres.',
        ];
    }
}

