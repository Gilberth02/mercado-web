<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Encoders\WebpEncoder;

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

   
     // Guarda un nuevo producto en la base de datos (y convierte la imagen).
    
    public function store(Request $request)
    {
        //  Validación de todos los campos del formulario
        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path_imagen = null;

        //  Lógica de Imagen (Conversión a WebP)
        if ($request->hasFile('imagen')) {
            try {
                // Preferir Imagick si está disponible, sino GD
                if (extension_loaded('imagick')) {
                    $manager = ImageManager::imagick();
                } elseif (extension_loaded('gd')) {
                    $manager = ImageManager::gd();
                } else {
                    throw new \Exception('No image driver available (gd or imagick)');
                }

                // Crear y convertir la imagen a WebP (usar API v3: read en vez de make)
                $imagen = $manager->read($request->file('imagen')->getRealPath());
                $nombreUnico = (string) Str::uuid() . '.webp';
                $encoded = $imagen->encode(new WebpEncoder(80));
                $encoded->save(storage_path('app/public/productos/' . $nombreUnico));
                $path_imagen = 'productos/' . $nombreUnico;
            } catch (\Exception $e) {
                // Si falla la conversión (p. ej. falta GD), hacemos fallback: guardar el archivo original
                \Log::warning('Image conversion failed, falling back to original. '.$e->getMessage());
                $path_imagen = $request->file('imagen')->store('productos', 'public');
            }
        }

        //  Guardar en Base de Datos
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

        //  Redirigir de vuelta al panel con mensaje de éxito
        return redirect()->route('vendedor.panel')->with('success', '¡Producto subido! Está pendiente de revisión.');
    }

    
     // Muestra la tienda pública (solo productos publicados).
     
    public function indexPublico()
    {
        $productos = Producto::where('estado', 'publicado')
                            ->with('vendedor') // Carga la info del vendedor
                            ->get();
        
        return view('paginas.tienda', [
            'productos' => $productos
        ]);
    }
}