<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\Rol;  

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirige al usuario a la página de autenticación de Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Maneja el callback de Google después de la autenticación.
     */
    public function callback()
    {
        try {
            // Obtenemos los datos del usuario de Google
            $googleUser = Socialite::driver('google')->user();

            // Buscamos si ya existe un usuario con ese email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Si ya existe, solo lo logueamos
                Auth::login($user);
            } else {
                // Si no existe, lo creamos
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => null, // Queda nulo porque usa Google
                    'telefono' => null, // Google no nos da esto
                ]);

                // ¡IMPORTANTE! Asignamos el rol de cliente
                $rolCliente = Rol::where('nombre', 'cliente')->first();
                if ($rolCliente) {
                    $newUser->roles()->attach($rolCliente->id);
                }

                // Logueamos al nuevo usuario
                Auth::login($newUser);
            }

            // Redirigimos al dashboard (o a la ruta que quieras)
            return redirect(config('fortify.home'));

        } catch (\Exception $e) {
            // Si algo falla (ej: el usuario cancela), lo regresamos al login
            return redirect(route('login'));
        }
    }
}
