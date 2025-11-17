<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductoController extends Controller
{
    /**
     * Muestra el formulario para crear un nuevo producto.
     * Carga las categorías para el <select>.
     */
    public function create()
    {
        // 1. Obtenemos todas las categorías de la BD
        $categorias = Categoria::all();
        
        // 2. Pasamos las categorías a la vista
        return view('paginas.vendedor', [
            'categorias' => $categorias
        ]);
    }

    /**
     * Guarda un nuevo producto en la base de datos (y convierte la imagen).
     */
    public function store(Request $request)
    {
        // 1. Validación de todos los campos del formulario
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path_imagen = null;

        // 2. Lógica de Imagen (Conversión a WebP)
        if ($request->hasFile('imagen')) {
            $manager = new ImageManager(new Driver());
            $imagen = $manager->read($request->file('imagen'));
            $nombreUnico = (string) Str::uuid() . '.webp';
            
            // Convierte y guarda la imagen en formato WebP (calidad 80)
            $imagen->toWebp(80)->save(storage_path('app/public/productos/' . $nombreUnico));
            
            $path_imagen = 'productos/' . $nombreUnico;
        }

        // 3. Guardar en Base de Datos
        Producto::create([
            'vendedor_id' => Auth::user()->vendedor->user_id, // ID del Vendedor
            'categoria_id' => $request->categoria_id,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'imagen' => $path_imagen,
            'estado' => 'pendiente', // ¡Lógica de moderación!
            'activo' => true,
        ]);

        // 4. Redirigir de vuelta al panel con mensaje de éxito
        return redirect()->route('vendedor.panel')->with('success', '¡Producto subido! Está pendiente de revisión.');
    }
}