<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactosProfesoreRequest extends FormRequest
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
        $id = $this->route('contactos_profesore')?->id_contacto; // binding

        return [
            'correo'     => ['required','email','max:100', Rule::unique('contactos_profesores','correo')->ignore($id,'id_contacto')],
            'telefono'   => ['required','string','max:20'],
            'direccion'  => ['required','string','max:120'],
            'fk_profesor'=> ['nullable','integer','exists:profesores,id_profesor'], // usa required si quieres forzarlo
        ];
    }
}
