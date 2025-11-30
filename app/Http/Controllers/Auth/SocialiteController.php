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

            // DEBUG: Ver todos los datos que Google nos da
            \Log::info('Google User Data:', [
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
                'avatar_original' => $googleUser->avatar_original ?? 'no existe',
                'user_object' => $googleUser->user ?? 'no existe',
            ]);

            // Buscamos si ya existe un usuario con ese email
            $user = User::where('email', $googleUser->email)->first();

            // Obtener la URL del avatar (puede estar en getAvatar() o avatar)
            $avatarUrl = $googleUser->getAvatar() ?? $googleUser->avatar ?? null;
            
            \Log::info('Avatar URL obtenida:', ['url' => $avatarUrl]);

            if ($user) {
                // Si ya existe, actualizamos su foto de perfil siempre con la de Google
                $user->profile_photo_path = $avatarUrl;
                $user->save();
                
                \Log::info('Usuario actualizado:', [
                    'email' => $user->email,
                    'profile_photo_path' => $user->profile_photo_path
                ]);
                
                // Lo logueamos
                Auth::login($user, true);
            } else {
                // Si no existe, lo creamos
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => null, // Queda nulo porque usa Google
                    'telefono' => null, // Google no nos da esto
                    'profile_photo_path' => $avatarUrl, // Guardamos el avatar de Google
                ]);

                \Log::info('Usuario creado:', [
                    'email' => $user->email,
                    'profile_photo_path' => $user->profile_photo_path
                ]);

                // Asignamos el rol de cliente por defecto
                $rolCliente = Rol::where('nombre', 'cliente')->first();
                if ($rolCliente) {
                    $user->roles()->syncWithoutDetaching([$rolCliente->id]);
                }

                // Logueamos al nuevo usuario
                Auth::login($user, true);
            }

            // Redirigimos a la página cliente (opciones para convertirse en vendedor o delivery)
            return redirect()->route('cliente.redirect');

        } catch (\Exception $e) {
            // Loguear el error para diagnóstico y regresar al login con mensaje
            \Log::error('Socialite Google callback error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Error en inicio con Google');
        }
    }
}
