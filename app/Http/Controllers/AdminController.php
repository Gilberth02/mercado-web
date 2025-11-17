<?php

namespace App\Http\Controllers;

use App\Models\Producto; // Importamos el modelo Producto
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{

    public function index()
    {
        // Buscamos productos 'pendientes' y cargamos la info del vendedor
        $productosPendientes = Producto::where('estado', 'pendiente')
                                    ->with('vendedor') 
                                    ->get();
        
        // Pasamos los productos a la nueva vista de admin
        return view('admin.productos', [
            'productosPendientes' => $productosPendientes
        ]);
    }

    
    //Cambia el estado de un producto a 'publicado'.
     
    public function aprobar(Producto $producto)
    {
        $producto->estado = 'publicado';
        $producto->save();

        return redirect()->route('admin.productos.index')->with('success', 'Producto aprobado y publicado.');
    }

    
     // Elimina un producto que ha sido rechazado.
     
    public function rechazar(Producto $producto)
    {

        $producto->delete();
        
        return redirect()->route('admin.productos.index')->with('success', 'Producto rechazado y eliminado.');
    }

    public function showRepartidores()
    {
        // Buscamos todos los Usuarios que tengan el rol 'repartidor'
        $repartidores = User::whereHas('roles', function ($query) {
                                $query->where('nombre', 'repartidor');
                            })
                            ->with('repartidor')
                            ->get();
        
        // Pasamos los datos a la nueva vista
        return view('admin.repartidores', [
            'repartidores' => $repartidores
        ]);
    }
    
    public function showCategorias()
    {
        //  Obtenemos todas las categorías existentes
        $categorias = Categoria::all();
        
        //  Pasamos las categorías a la nueva vista
        return view('admin.categorias', [
            'categorias' => $categorias
        ]);
    }

    
     //Guarda la nueva categoría en la base de datos.
     
    public function storeCategoria(Request $request)
    {
        // Validamos que el nombre sea único y requerido
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias'
        ]);

        // Creamos la nueva categoría
        Categoria::create([
            'nombre' => $request->nombre
        ]);

        // Redirigimos de vuelta con un mensaje de éxito
        return redirect()->route('admin.categorias.index')->with('success', '¡Categoría creada exitosamente!');
    }
}