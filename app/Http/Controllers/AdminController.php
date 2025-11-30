<?php

namespace App\Http\Controllers;

use App\Models\Producto; // Importamos el modelo Producto
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Repartidor;
use App\Models\Rol;

class AdminController extends Controller
{

    public function index()
    {
                // Buscamos productos pendientes, publicados o con propuestas de edición (para revisión)
                $productos = Producto::where(function($q){
                                                                $q->whereIn('estado', ['pendiente', 'publicado'])
                                                                    ->orWhereNotNull('propuesta_edicion');
                                                        })
                                                        ->with('vendedor')
                                                        ->orderBy('created_at', 'desc')
                                                        ->get();

        // Pasamos los productos a la vista de admin
        return view('admin.productos', [
            'productos' => $productos
        ]);
    }

    
    //Cambia el estado de un producto a 'publicado'.
     
    public function aprobar(Producto $producto)
    {
        // Si existe una propuesta de edición, aplicarla
        if ($producto->propuesta_edicion) {
            $propuesta = json_decode($producto->propuesta_edicion, true);
            if (is_array($propuesta)) {
                foreach (['nombre','descripcion','precio','stock','categoria_id','imagen'] as $key) {
                    if (array_key_exists($key, $propuesta)) {
                        $producto->{$key} = $propuesta[$key];
                    }
                }
            }
            // limpiar propuesta
            $producto->propuesta_edicion = null;
        }

        // Limpiamos cualquier motivo de rechazo anterior al publicar
        $producto->rechazo_motivo = null;
        $producto->estado = 'publicado';
        $producto->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto aprobado y publicado.'
            ]);
        }

        return redirect()->route('admin.productos.index')->with('success', 'Producto aprobado y publicado.');
    }


    /**
     * Alterna el estado `activo` de un producto (habilitar / deshabilitar)
     */
    public function toggleActivo(Producto $producto)
    {
        $producto->activo = !$producto->activo;
        $producto->save();

        $mensaje = $producto->activo ? 'Producto habilitado.' : 'Producto deshabilitado.';
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $mensaje
            ]);
        }
        
        return redirect()->route('admin.productos.index')->with('success', $mensaje);
    }

    
     // Elimina un producto que ha sido rechazado.
     
    public function rechazar(\Illuminate\Http\Request $request, Producto $producto)
    {
        // Si el producto está en estado 'pendiente' (solicitud de creación/edición),
        // guardamos el motivo de rechazo y marcamos como 'rechazado' en lugar de eliminarlo.
        // Si existe una propuesta de edición (incluso si el producto sigue publicado), guardamos el motivo
        // y descartamos la propuesta, dejando el producto publicado.
        if ($producto->propuesta_edicion) {
            $request->validate([
                'motivo' => 'required|string|max:2000'
            ]);

            $producto->rechazo_motivo = $request->input('motivo');
            $producto->propuesta_edicion = null;
            // mantener estado tal como está (normalmente 'publicado')
            $producto->save();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud de edición rechazada. Se guardó el motivo.'
                ]);
            }
            return redirect()->route('admin.productos.index')->with('success', 'Solicitud de edición rechazada. Se guardó el motivo.');
        }

        // Si el producto está en estado 'pendiente' (nueva creación), marcamos como rechazado (sin eliminar)
        if ($producto->estado === 'pendiente') {
            $request->validate([
                'motivo' => 'required|string|max:2000'
            ]);

            $producto->estado = 'rechazado';
            $producto->rechazo_motivo = $request->input('motivo');
            $producto->save();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Producto rechazado. Se guardó el motivo.'
                ]);
            }
            return redirect()->route('admin.productos.index')->with('success', 'Producto rechazado. Se guardó el motivo.');
        }

        // Para otros estados, si el admin desea eliminarlo completamente, mantenemos la eliminación.
        $producto->delete();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado.'
            ]);
        }
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado.');
    }

    public function showRepartidores()
    {
        // Listado mixto: pendientes (sin rol) y aprobados (con rol)
        $pendientes = Repartidor::where('estado', 'pendiente')->with('user')->get();
        $aprobados = User::whereHas('roles', function ($query) {
                                $query->where('nombre', 'repartidor');
                            })
                            ->with('repartidor')
                            ->get();
        
        // Pasamos los datos a la nueva vista
        return view('admin.repartidores', [
            'pendientes' => $pendientes,
            'aprobados' => $aprobados
        ]);
    }

    public function aprobarRepartidor(Repartidor $repartidor)
    {
        $user = $repartidor->user;
        $rol = Rol::where('nombre', 'repartidor')->first();
        if ($rol && $user && ! $user->roles()->where('rol_id', $rol->id)->exists()) {
            $user->roles()->attach($rol->id);
        }
        $repartidor->estado = 'aprobado';
        $repartidor->save();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Repartidor aprobado.'
            ]);
        }
        return redirect()->route('admin.repartidores.index')->with('success', 'Repartidor aprobado.');
    }

    public function rechazarRepartidor(Request $request, Repartidor $repartidor)
    {
        // Opcional: motivo
        $repartidor->estado = 'rechazado';
        $repartidor->save();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de repartidor rechazada.'
            ]);
        }
        return redirect()->route('admin.repartidores.index')->with('success', 'Solicitud de repartidor rechazada.');
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
        $categoria = Categoria::create([
            'nombre' => $request->nombre
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '¡Categoría creada exitosamente!',
                'categoria' => $categoria
            ]);
        }

        // Redirigimos de vuelta con un mensaje de éxito
        return redirect()->route('admin.categorias.index')->with('success', '¡Categoría creada exitosamente!');
    }
}