<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;

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
        $profId = $this->fk_profesor;

        // ignora al user ya vinculado a ese profesor (si existe)
        $userIdVinculado = optional(User::where('profesor_id', $profId)->first())->id;

        return [
            'correo' => [
                'bail','required','email','max:100',
                Rule::unique('contactos_profesores','correo')->ignore($id,'id_contacto'),
                Rule::unique('users','email')->ignore($userIdVinculado),
            ],
            'telefono'   => ['required','string','max:20','regex:/^[0-9\-\+\(\)\s]{7,20}$/'],
            'direccion'  => ['required','string','max:120'],
            'fk_profesor'=> ['nullable','integer','exists:profesores,id_profesor'], // usa required si quieres forzarlo
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'correo' => $this->correo ? mb_strtolower(trim($this->correo)) : null,
        ]);
    }

}
