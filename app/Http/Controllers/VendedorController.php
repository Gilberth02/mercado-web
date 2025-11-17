<?php

namespace App\Http\Controllers;

use App\Models\Rol;       // Importamos Rol
use App\Models\Vendedor;  // Importamos Vendedor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importamos Auth

class VendedorController extends Controller
{
    
     //Muestra el formulario de registro de vendedor.
     
    public function showRegistro()
    {
        // Si el usuario ya es vendedor, lo redirigimos
        if (Auth::user()->vendedor) {
            // Asumimos que la ruta del panel de vendedor se llama 'vendedor.panel'
            return redirect(route('vendedor.panel')); 
        }
        
        return view('paginas.vendedor-registro');
    }

    //Guarda los datos del nuevo vendedor.
     
    public function storeRegistro(Request $request)
    {
        $request->validate([
            'nombre_negocio' => 'required|string|max:150',
            // Añade más validaciones aquí si añadiste más campos
        ]);

        $user = Auth::user();

        // 1. Creamos el perfil en la tabla 'vendedores'
        Vendedor::create([
            'user_id' => $user->id,
            'nombre_negocio' => $request->nombre_negocio,
        ]);

        // 2. Buscamos el rol 'vendedor'
        $rolVendedor = Rol::where('nombre', 'vendedor')->first();

        // 3. Le asignamos el nuevo rol (sin quitar el de 'cliente')
        if ($rolVendedor) {
            $user->roles()->attach($rolVendedor->id);
        }

        // 4. Redirigimos al panel de vendedor
        return redirect()->route('vendedor.panel')->with('success', '¡Felicidades! Ya eres vendedor.');
    }
}