<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    
    // Muestra la página del carrito de compras.
     
    public function index()
    {
        // session()->forget('cart'); // (Descomenta esto si necesitas limpiar el carrito)
        
        $cart = session()->get('cart', []);
        
        // Obtenemos los IDs de los productos en el carrito
        $productIds = array_keys($cart);
        
        // Buscamos los productos en la base de datos
        $productos = Producto::whereIn('id', $productIds)->get();
        
        // Calculamos el total
        $total = 0;
        foreach ($productos as $producto) {
            $cantidad = $cart[$producto->id]['quantity'];
            $total += $producto->precio * $cantidad;
        }

        // Cargar pedidos del usuario para mostrar 'Mis pedidos' en la página del carrito
        $pedidos = collect();
        if (Auth::check()) {
            $pedidos = Pedido::where('cliente_id', Auth::id())
                        ->with(['detalles.producto', 'asignacion.repartidor.user'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        }

        return view('paginas.carrito', [
            'productos' => $productos,
            'cart' => $cart,
            'total' => $total,
            'pedidos' => $pedidos,
        ]);
    }

    /**
     * Mostrar el formulario de checkout (teléfono y dirección).
     */
    public function checkoutForm()
    {
        $cart = session()->get('cart', []);
        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }
        return view('paginas.checkout');
    }

    /**
     * Procesar la compra: validar teléfono y dirección, crear pedido básico.
     */
    public function processCheckout(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string|max:30',
            'direccion' => 'required|string|max:1000'
        ]);

        $cart = session()->get('cart', []);
        if (count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        // Crear un pedido simple en transacción
        DB::beginTransaction();
        try {
            $userId = Auth::check() ? Auth::id() : null;

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Si el usuario está autenticado, actualizamos su teléfono en el perfil
            if ($userId && $request->filled('telefono')) {
                try {
                    $user = Auth::user();
                    $user->telefono = $request->input('telefono');
                    $user->save();
                } catch (\Exception $ex) {
                    // No detenemos el proceso por un fallo menor al actualizar el perfil
                    \Log::warning('No se pudo actualizar teléfono del usuario: ' . $ex->getMessage());
                }
            }

            $pedidoData = [
                'cliente_id' => $userId,
                'telefono' => $request->input('telefono'),
                'direccion' => $request->input('direccion'),
                'total' => $total,
                'estado' => 'pendiente'
            ];

            // Incluir lat/lng si vienen y si la tabla tiene las columnas
            if ($request->filled('lat') && $request->filled('lng')) {
                if (Schema::hasColumn('pedidos', 'lat') && Schema::hasColumn('pedidos', 'lng')) {
                    $pedidoData['lat'] = $request->input('lat');
                    $pedidoData['lng'] = $request->input('lng');
                }
            }

            $pedido = Pedido::create($pedidoData);

            // Guardar detalles (si existe tabla/relación DetallePedido)
            foreach ($cart as $productId => $item) {
                // Si existe el modelo DetallePedido y relación, intentamos crearlo
                if (class_exists(\App\Models\DetallePedido::class)) {
                    \App\Models\DetallePedido::create([
                        'pedido_id' => $pedido->id,
                        'producto_id' => $productId,
                        'cantidad' => $item['quantity'],
                        'precio' => $item['price']
                    ]);
                }
            }

            DB::commit();

            // Vaciar carrito
            session()->forget('cart');

            return redirect()->route('tienda.index')->with('success', 'Compra procesada. Nos comunicaremos para coordinar la entrega.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Error procesando la compra: ' . $e->getMessage()]);
        }
    }

    
     // Añade un producto al carrito.
     
    public function add(Producto $producto)
    {
        //  Obtenemos el carrito de la sesión, o un array vacío si no existe
        $cart = session()->get('cart', []);

        //  Revisamos si el producto ya está en el carrito
        if (isset($cart[$producto->id])) {
            // Si existe, solo aumentamos la cantidad
            $cart[$producto->id]['quantity']++;
        } else {
            // Si no existe, lo añadimos con cantidad 1
            $cart[$producto->id] = [
                "name" => $producto->nombre,
                "quantity" => 1,
                "price" => $producto->precio,
                "image" => $producto->imagen
            ];
        }

        //  Guardamos el carrito actualizado en la sesión
        session()->put('cart', $cart);

        //  Redirigimos de vuelta a la tienda con un mensaje
        return redirect()->route('tienda.index')->with('success', '¡Producto añadido al carrito!');
    }

    
    // Elimina un producto del carrito.
    public function remove(Request $request, Producto $producto)
    {
        $cart = session()->get('cart', []);

        // Si el producto existe en el carrito, lo eliminamos
        if (isset($cart[$producto->id])) {
            unset($cart[$producto->id]);
            session()->put('cart', $cart);
        }

        // Calcular total actual
        $total = 0;
        foreach (session()->get('cart', []) as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito.', 'total' => $total]);
        }

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $qty = (int) $request->input('quantity');
        $cart = session()->get('cart', []);

        if ($qty <= 0) {
            // Si la cantidad es 0, removemos el producto
            if (isset($cart[$producto->id])) {
                unset($cart[$producto->id]);
                session()->put('cart', $cart);
            }
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito.']);
            }
            return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
        }

        // Validar stock básico si aplica
        if ($producto->stock !== null && $qty > $producto->stock) {
            return redirect()->route('cart.index')->with('error', 'No hay suficiente stock para esa cantidad.');
        }

        // Actualizar o añadir
        if (isset($cart[$producto->id])) {
            $cart[$producto->id]['quantity'] = $qty;
        } else {
            $cart[$producto->id] = [
                'name' => $producto->nombre,
                'quantity' => $qty,
                'price' => $producto->precio,
                'image' => $producto->imagen,
            ];
        }

        session()->put('cart', $cart);

        // Recalcular subtotal y total para respuesta AJAX
        $subtotal = $producto->precio * $qty;
        $total = 0;
        foreach ($cart as $id => $item) {
            $total += $item['price'] * $item['quantity'];
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'quantity' => $qty, 'subtotal' => $subtotal, 'total' => $total]);
        }

        return redirect()->route('cart.index')->with('success', 'Cantidad actualizada.');
    }

    /**
     * Vacía todo el carrito de la sesión.
     */
    public function clear(Request $request)
    {
        session()->forget('cart');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Carrito vaciado.', 'total' => 0]);
        }

        return redirect()->route('cart.index')->with('success', 'Carrito vaciado.');
    }
}