<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Obtenemos el usuario que está logueado
        $user = $request->user();

        // 2. Si no está logueado, lo mandamos al login
        if (! $user) {
            return redirect(route('login'));
        }

        // 3. Revisamos si el usuario tiene ALGUNO de los roles requeridos
        foreach ($roles as $role) {
            // Usamos la relación roles() que definimos en el Modelo User
            if ($user->roles()->where('nombre', $role)->exists()) {
                // ¡Tiene el rol! Dejamos que continúe
                return $next($request);
            }
        }

        // 4. Si el bucle termina, significa que no tuvo el rol.
        // Lo redirigimos al panel cliente con un mensaje de error.
        return redirect()->route('cliente.redirect')->with('error', 'No tienes permiso para acceder a esa sección.');
    }
}