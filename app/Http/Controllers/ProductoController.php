<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        
        // 2. Obtenemos los productos de este vendedor (si tiene perfil de vendedor)
        $productos = collect();
        if (Auth::user() && Auth::user()->vendedor) {
            $vendedorId = Auth::user()->vendedor->user_id;
            $productos = Producto::where('vendedor_id', $vendedorId)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // 3. Obtenemos los pedidos que contienen productos de este vendedor
        $pedidos = collect();
        if (Auth::user() && Auth::user()->vendedor) {
            $vendedorId = Auth::user()->vendedor->user_id;
            $pedidos = \App\Models\Pedido::whereHas('detalles.producto', function($q) use ($vendedorId) {
                $q->where('vendedor_id', $vendedorId);
            })->with(['detalles.producto.vendedor.user', 'asignacion.repartidor.user', 'cliente'])
            ->orderBy('created_at', 'desc')
            ->get();
        }

        // 4. Pasamos las categorías, productos y pedidos a la vista
        return view('paginas.vendedor', [
            'categorias' => $categorias,
            'productos' => $productos,
            'pedidos' => $pedidos,
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
     
    public function indexPublico(Request $request)
    {
        $query = Producto::where('estado', 'publicado')
                         ->where('activo', true)
                         ->with(['vendedor', 'resenas']);
        
        // Filtro de búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('descripcion', 'like', '%' . $buscar . '%');
            });
        }
        
        // Filtro de categoría
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }
        
        // Ordenamiento
        $orden = $request->get('orden', 'reciente');
        switch ($orden) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'nombre':
                $query->orderBy('nombre', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $productos = $query->paginate(12)->withQueryString();
        $categorias = \App\Models\Categoria::all();
        
        return view('paginas.tienda', [
            'productos' => $productos,
            'categorias' => $categorias
        ]);
    }

    /**
     * Alterna el campo `activo` de un producto.
     * Sólo el vendedor propietario puede hacerlo.
     */
    public function toggleActivo(Request $request, Producto $producto)
    {
        $user = Auth::user();

        // Verificar que el usuario sea vendedor y propietario del producto
        if (!$user || !$user->vendedor || $user->vendedor->user_id !== $producto->vendedor_id) {
            abort(403, 'No autorizado.');
        }

        $producto->activo = !$producto->activo;
        $producto->save();

        // Si la petición es AJAX, devolvemos JSON para actualización en cliente
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'activo' => (bool) $producto->activo,
                'mensaje' => 'Estado de producto actualizado.'
            ]);
        }

        return redirect()->route('vendedor.panel')->with('success', 'Estado de producto actualizado.');
    }

    /**
     * Mostrar formulario de edición para un producto del vendedor.
     */
    public function edit(Producto $producto)
    {
        $user = Auth::user();
        if (!$user || !$user->vendedor || $user->vendedor->user_id !== $producto->vendedor_id) {
            abort(403, 'No autorizado.');
        }

        $categorias = Categoria::all();
        return view('paginas.vendedor_edit', [
            'producto' => $producto,
            'categorias' => $categorias,
        ]);
    }

    /**
     * Actualiza un producto. Los cambios quedan en estado 'pendiente' para aprobación.
     */
    public function update(Request $request, Producto $producto)
    {
        $user = Auth::user();
        if (!$user || !$user->vendedor || $user->vendedor->user_id !== $producto->vendedor_id) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Procesar imagen nueva si existe
        $nuevaImagenPath = null;
        if ($request->hasFile('imagen')) {
            try {
                if (extension_loaded('imagick')) {
                    $manager = ImageManager::imagick();
                } elseif (extension_loaded('gd')) {
                    $manager = ImageManager::gd();
                } else {
                    throw new \Exception('No image driver available (gd or imagick)');
                }

                $imagen = $manager->read($request->file('imagen')->getRealPath());
                $nombreUnico = (string) Str::uuid() . '.webp';
                $encoded = $imagen->encode(new WebpEncoder(80));
                $encoded->save(storage_path('app/public/productos/' . $nombreUnico));

                // Para la propuesta guardamos la nueva imagen pero NO sobrescribimos la imagen actual del producto
                $nuevaImagenPath = 'productos/' . $nombreUnico;
            } catch (\Exception $e) {
                \Log::warning('Image conversion failed on update, falling back. '.$e->getMessage());
                $path = $request->file('imagen')->store('productos', 'public');
                $nuevaImagenPath = $path;
            }
        }

        // Si el producto está publicado, no sobrescribimos sus datos visibles:
        // guardamos la propuesta de edición en JSON y marcamos como 'pendiente'.
        if ($producto->estado === 'publicado') {
            $propuesta = [
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock' => $request->stock,
                'categoria_id' => $request->categoria_id,
            ];
            if ($nuevaImagenPath) {
                $propuesta['imagen'] = $nuevaImagenPath;
            }

            // Guardamos la propuesta sin modificar el producto publicado hasta que el admin apruebe
            $producto->propuesta_edicion = json_encode($propuesta);
            $producto->save();

            return redirect()->route('vendedor.panel')->with('success', 'Propuesta de edición enviada. Un administrador revisará y aprobará los cambios.');
        }

        // Para productos que no estaban publicados, aplicamos los cambios directamente y los marcamos como pendientes.
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->stock = $request->stock;
        $producto->categoria_id = $request->categoria_id;
        if ($nuevaImagenPath) {
            // eliminar imagen anterior si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $producto->imagen = $nuevaImagenPath;
        }

        // Marcar como pendiente para que el admin revise los cambios
        $producto->estado = 'pendiente';
        $producto->save();

        return redirect()->route('vendedor.panel')->with('success', 'Cambios guardados. Un administrador revisará la actualización.');
    }

    /**
     * Eliminar un producto (propietario vendedor).
     */
    public function destroy(Producto $producto)
    {
        $user = Auth::user();
        if (!$user || !$user->vendedor || $user->vendedor->user_id !== $producto->vendedor_id) {
            abort(403, 'No autorizado.');
        }

        // Eliminar imagen del storage si existe
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('vendedor.panel')->with('success', 'Producto eliminado.');
    }

    public function show(Producto $producto)
    {
        // Verificamos que esté publicado y activo (por seguridad)
        if ($producto->estado !== 'publicado' || !$producto->activo) {
            abort(404); // O redirigir a la tienda
        }

        // Cargamos las reseñas y el vendedor para usarlos en la vista
        $producto->load(['vendedor', 'resenas.user']); 

        return view('paginas.producto-detalle', [
            'producto' => $producto
        ]);
    }
}