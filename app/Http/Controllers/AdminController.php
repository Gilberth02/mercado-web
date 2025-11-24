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
                // Buscamos productos pendientes, publicados o con propuestas de edición (para revisión)
                $productos = Producto::where(function($q){
                                                                $q->whereIn('estado', ['pendiente', 'publicado'])
                                                                    ->orWhereNotNull('propuesta_edicion');
                                                        })
                                                        ->with('vendedor')
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
            return redirect()->route('admin.productos.index')->with('success', 'Producto rechazado. Se guardó el motivo.');
        }

        // Para otros estados, si el admin desea eliminarlo completamente, mantenemos la eliminación.
        $producto->delete();
        return redirect()->route('admin.productos.index')->with('success', 'Producto eliminado.');
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