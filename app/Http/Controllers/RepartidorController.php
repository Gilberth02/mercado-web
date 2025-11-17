<?php

namespace App\Http\Controllers;

use App\Models\Rol;         // Importamos Rol
use App\Models\Repartidor;  // Importamos Repartidor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importamos Auth

class RepartidorController extends Controller
{
    /**
     * Muestra el formulario de registro de repartidor.
     */
    public function showRegistro()
    {
        // Si el usuario ya es repartidor, lo redirigimos
        if (Auth::user()->repartidor) {
            return redirect(route('repartidor.panel'));
        }
        
        return view('paginas.repartidor-registro');
    }

    /**
     * Guarda los datos del nuevo repartidor.
     */
    public function storeRegistro(Request $request)
    {
        $request->validate([
            'vehiculo' => 'required|string|max:100',
            'matricula' => 'required|string|max:20',
        ]);

        $user = Auth::user();

        // 1. Creamos el perfil en la tabla 'repartidores'
        Repartidor::create([
            'user_id' => $user->id,
            'vehiculo' => $request->vehiculo,
            'matricula' => $request->matricula,
            'disponible' => true, // Lo ponemos disponible por defecto
        ]);

        // 2. Buscamos el rol 'repartidor'
        $rolRepartidor = Rol::where('nombre', 'repartidor')->first();

        // 3. Le asignamos el nuevo rol
        if ($rolRepartidor) {
            $user->roles()->attach($rolRepartidor->id);
        }

        // 4. Redirigimos al panel de repartidor
        return redirect()->route('repartidor.panel')->with('success', '¡Felicidades! Ya eres repartidor.');
    }
}