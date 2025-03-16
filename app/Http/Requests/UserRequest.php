<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permite el acceso a la validación
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . ($this->id ? $this->id : 'NULL'), 'max:150'],
            'tipo_doc' => ['required', 'in:dni,ce,pasaporte'], // Solo permite estos valores
            'num_doc' => ['required', 'string', 'max:12', function ($attribute, $value, $fail) {
                if ($this->tipo_doc === 'dni' && !preg_match('/^\d{8}$/', $value)) {
                    return $fail('El DNI debe tener 8 dígitos.');
                }
                if ($this->tipo_doc === 'ce' && !preg_match('/^\d{9}$/', $value)) {
                    return $fail('El Carnet de Extranjería debe tener 9 dígitos.');
                }
                if ($this->tipo_doc === 'pasaporte' && !preg_match('/^[A-Za-z0-9]{6,12}$/', $value)) {
                    return $fail('El Pasaporte debe tener entre 6 y 12 caracteres.');
                }
            }],
            'celular' => ['required', 'regex:/^\d{9}$/'], // 9 dígitos numéricos
            'password' => ['nullable'], // Laravel generará el password automáticamente con num_doc
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',
            'tipo_doc.required' => 'El tipo de documento es obligatorio.',
            'tipo_doc.in' => 'El tipo de documento debe ser DNI, CE o Pasaporte.',
            'num_doc.required' => 'El número de documento es obligatorio.',
            'celular.required' => 'El número de celular es obligatorio.',
            'celular.regex' => 'El celular debe tener 9 dígitos numéricos.',
        ];
    }
}
