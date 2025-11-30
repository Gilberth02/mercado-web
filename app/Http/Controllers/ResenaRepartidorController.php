<?php

namespace App\Http\Controllers;

use App\Models\Repartidor;
use App\Models\ResenaRepartidor;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaRepartidorController extends Controller
{
    public function store(Request $request, $repartidorId)
    {
        $request->validate([
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:500',
            'pedido_id' => 'required|exists:pedidos,id',
        ]);

        $pedido = Pedido::findOrFail($request->pedido_id);

        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->cliente_id !== Auth::id()) {
            return back()->with('error', 'No puedes calificar este delivery.');
        }

        // Verificar que el pedido fue entregado
        if ($pedido->estado !== 'entregado') {
            return back()->with('error', 'Solo puedes calificar deliveries de pedidos entregados.');
        }

        // Verificar que el pedido fue asignado a este repartidor
        if (!$pedido->asignacion || $pedido->asignacion->repartidor_id != $repartidorId) {
            return back()->with('error', 'Este repartidor no entregó tu pedido.');
        }

        // Evitar duplicados (un usuario solo califica una vez por pedido/repartidor)
        $yaCalifico = ResenaRepartidor::where('user_id', Auth::id())
                            ->where('repartidor_id', $repartidorId)
                            ->where('pedido_id', $pedido->id)
                            ->exists();

        if ($yaCalifico) {
            return back()->with('error', 'Ya has calificado este delivery.');
        }

        // Crear la reseña
        ResenaRepartidor::create([
            'user_id' => Auth::id(),
            'repartidor_id' => $repartidorId,
            'pedido_id' => $pedido->id,
            'puntuacion' => $request->puntuacion,
            'comentario' => $request->comentario
        ]);

        return back()->with('success', '¡Gracias por calificar el servicio de delivery!');
    }
}
