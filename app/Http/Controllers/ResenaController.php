<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaController extends Controller
{
    public function store(Request $request, Producto $producto)
    {
        $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
        ]);

        // --- VALIDACIÓN OPCIONAL: ¿Compró el producto? ---
        // Buscamos si el usuario tiene un pedido entregado que contenga este producto
        $haComprado = Auth::user()->pedidos()
            ->where('estado', 'entregado') // Solo si ya le llegó
            ->whereHas('detalles', function($query) use ($producto) {
                $query->where('producto_id', $producto->id);
            })->exists();

        if (!$haComprado) {
            // Si quieres ser estricto, descomenta esto:
            // return back()->with('error', 'Solo puedes calificar productos que has comprado.');
        }
        // --------------------------------------------------

        // Evitar duplicados (un usuario solo califica una vez por producto)
        $yaCalifico = Resena::where('user_id', Auth::id())
                            ->where('producto_id', $producto->id)
                            ->exists();

        if ($yaCalifico) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya has calificado este producto.'
                ], 400);
            }
            return back()->with('error', 'Ya has calificado este producto.');
        }

        // Crear la reseña
        $resena = Resena::create([
            'user_id' => Auth::id(),
            'producto_id' => $producto->id,
            'puntuacion' => $request->puntuacion,
            'comentario' => $request->comentario
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '¡Gracias por tu opinión!',
                'resena' => [
                    'user_name' => Auth::user()->name,
                    'puntuacion' => $resena->puntuacion,
                    'comentario' => $resena->comentario,
                    'fecha' => $resena->created_at->format('d/m/Y')
                ]
            ]);
        }

        return back()->with('success', '¡Gracias por tu opinión!');
    }
}