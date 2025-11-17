<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        //return view('auth.login');
        return view('paginas.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirigir según rol del usuario autenticado.
        $user = $request->user();

        // Si el usuario es admin -> panel de admin
        if ($user && $user->roles()->where('nombre', 'admin')->exists()) {
            return redirect()->intended(route('admin.productos.index'));
        }

        // Si es vendedor -> panel de vendedor
        if ($user && $user->roles()->where('nombre', 'vendedor')->exists()) {
            return redirect()->intended(route('vendedor.panel'));
        }

        // Si es repartidor -> panel de repartidor
        if ($user && $user->roles()->where('nombre', 'repartidor')->exists()) {
            return redirect()->intended(route('repartidor.panel'));
        }

        // Por defecto, usar el HOME configurado (cliente)
        return redirect()->intended(\App\Providers\AppServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
