<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Rol; //linea añadida

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        //return view('auth.register');
        return view('paginas.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // <-- 2. AÑADE ESTA LÓGICA
        // Buscamos el rol de 'cliente'.
        $rolCliente = Rol::where('nombre', 'cliente')->first();

        // Si existe el rol, se lo asignamos al usuario
        if ($rolCliente) {
            $user->roles()->attach($rolCliente->id);
        }
        // <-- FIN DE LA LÓGICA AÑADIDA

        event(new Registered($user));

        Auth::login($user);

        // Esta línea puede variar, Breeze la configura automáticamente
        return redirect(config('fortify.home'));
    }
}
