<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

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

        return view('paginas.carrito', [
            'productos' => $productos,
            'cart' => $cart,
            'total' => $total
        ]);
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
    
    public function remove(Producto $producto)
    {
        $cart = session()->get('cart', []);

        // Si el producto existe en el carrito, lo eliminamos
        if (isset($cart[$producto->id])) {
            unset($cart[$producto->id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Producto eliminado del carrito.');
    }
}