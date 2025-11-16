<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        // Detecta si es create o update
        $isUpdate = in_array($this->method(), ['PUT','PATCH'], true);

        // Si tienes route model binding: Route::resource('alumnos', ...)
        // y la clave es no_control, esto te trae el modelo actual en update.
        $alumno = $this->route('alumno'); // puede ser null en create
        $contactoId = optional(optional($alumno)->contacto)->id_contacto;

        // Correo anterior del contacto (para ignorarlo en unique de users)
        $oldMail = optional(optional($alumno)->contacto)->correo;

        $noControlRules = ['sometimes','string','max:24'];
        if ($isUpdate && $alumno) {
            // único, ignorando el actual por columna no_control
            $noControlRules[] = Rule::unique('alumnos','no_control')
                                    ->ignore($alumno->no_control, 'no_control');
        } else {
            // en create, si lo envías, que sea único
            $noControlRules[] = Rule::unique('alumnos','no_control');
        }

        return [
            // Opcional (lo generas en el modelo); si lo mandas, se valida:
            'no_control'   => $noControlRules,

            'nombre'       => ['required','string','max:45'],
            'apellido_pat' => ['required','string','max:45'],
            'apellido_mat' => ['required','string','max:45'],

            'genero'       => ['required','in:M,F'],
            'anio'         => ['required','integer','between:2000,2100'],
            'periodo'      => ['required','integer','in:1,2'], // 1: Ene-Jun, 2: Ago-Dic
            'consecutivo'  => ['sometimes','nullable','integer'],

            // ✅ ahora opcional y va a la pivot alumno_carrera
            'carrera_id'   => ['sometimes','nullable','integer','exists:carreras,id_carrera'],
            'semestre' => ['required','integer','between:1,20'],

            // Contacto opcional
            // Contacto opcional
            'correo' => [
                'nullable','email','max:100',
                // único en contactos_alumnos, ignorando su propio id al editar
                Rule::unique('contactos_alumnos', 'correo')->ignore($contactoId, 'id_contacto'),
                // único también en users.email, ignorando el correo previo del contacto al editar
                Rule::unique('users', 'email')->ignore($oldMail, 'email'),
            ],
            'telefono'      => 'nullable|string|max:20',
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

    // (Opcional) mensajes en español
     public function messages(): array
     {
         return [
            'no_control.unique'   => 'El número de control ya existe.',
            'nombre.required'     => 'El nombre es obligatorio.',
            'apellido_pat.required'=> 'El apellido paterno es obligatorio.',
            'apellido_mat.required'=> 'El apellido materno es obligatorio.',
            'genero.in'           => 'El género debe ser M o F.',
            'anio.between'        => 'El año debe estar entre 2000 y 2100.',
            'periodo.in'          => 'Periodo debe ser 1 (Ene-Jun) o 2 (Ago-Dic).',
            'carrera_id.exists'   => 'La carrera seleccionada no existe.',
        ];
     }

     public function attributes(): array
    {
        return [
            'no_control'   => 'número de control',
            'apellido_pat' => 'apellido paterno',
            'apellido_mat' => 'apellido materno',
            'carrera_id'   => 'carrera',
        ];
    }

}
