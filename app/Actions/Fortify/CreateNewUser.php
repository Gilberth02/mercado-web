<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            // Solo letras y espacios (incluye acentos), sin números
            'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]+$/'],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                // Solo correos @gmail.com o @hotmail.com
                'regex:/^[\\w.+-]+@(gmail\\.com|hotmail\\.com)$/',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ], [
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.email' => 'El correo debe ser válido (formato RFC).',
            'email.regex' => 'Solo se permiten correos @gmail.com o @hotmail.com.',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
