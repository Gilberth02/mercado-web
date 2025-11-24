<?php

namespace App\Http\Controllers;

use App\Models\Rol;         // Importamos Rol
use App\Models\Repartidor;  // Importamos Repartidor
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Importamos Auth
use App\Models\Pedido;
use App\Models\AsignacionEnvio;
use Illuminate\Http\RedirectResponse;

class RepartidorController extends Controller
{
    /**
     * Muestra el formulario de registro de repartidor.
     */
    public function showRegistro()
    {
        // Si el usuario ya es repartidor, lo redirigimos
        if (Auth::user()->repartidor) {
            return redirect(route('repartidor.panel'));
        }
        
        return view('paginas.repartidor-registro');
    }

    /**
     * Muestra el panel principal de repartidor con pedidos disponibles y asignados.
     */
    public function panel()
    {
        $userId = Auth::id();

        // Pedidos sin asignación y con estado pendiente
        $pendientes = Pedido::with(['detalles.producto.vendedor.user', 'cliente'])
            ->where('estado', 'pendiente')
            ->whereDoesntHave('asignacion')
            ->orderBy('created_at', 'desc')
            ->get();

        // Asignaciones actuales para este repartidor (repartidor_id almacena el user_id)
        $asignados = AsignacionEnvio::with(['pedido.detalles.producto.vendedor.user', 'pedido.cliente'])
            ->where('repartidor_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('paginas.delivery', [
            'pendientes' => $pendientes,
            'asignados' => $asignados
        ]);
    }

    /**
     * El repartidor reclama (se asigna) un pedido.
     */
    public function asignar(Request $request, Pedido $pedido): RedirectResponse
    {
        $userId = Auth::id();

        // Evitar que otro repartidor lo reclame si ya existe una asignación
        if ($pedido->asignacion) {
            return redirect()->back()->with('error', 'El pedido ya fue asignado.');
        }

        $asig = AsignacionEnvio::create([
            'pedido_id' => $pedido->id,
            'repartidor_id' => $userId,
            'estado' => 'asignado'
        ]);

        $pedido->estado = 'asignado';
        $pedido->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pedido asignado a ti.',
                'asignacion_id' => $asig->id,
                'estado' => 'asignado',
                'pedido_id' => $pedido->id
            ]);
        }

        return redirect()->route('repartidor.panel')->with('success', 'Pedido asignado a ti.');
    }

    /**
     * Marcar un pedido como entregado.
     */
    public function marcarEntregado(Request $request, Pedido $pedido): RedirectResponse
    {
        $userId = Auth::id();

        $asignacion = $pedido->asignacion;
        if (! $asignacion || $asignacion->repartidor_id != $userId) {
            return redirect()->back()->with('error', 'No autorizado para marcar este pedido.');
        }

        // Actualizamos estados
        $pedido->estado = 'entregado';
        $pedido->save();

        $asignacion->estado = 'entregado';
        $asignacion->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pedido marcado como entregado.', 'estado' => 'entregado']);
        }

        return redirect()->route('repartidor.panel')->with('success', 'Pedido marcado como entregado.');
    }

    /**
     * Marcar pedido como 'por recoger' (repartidor indica que está yendo a recoger).
     */
    public function marcarPorRecoger(Request $request, Pedido $pedido): RedirectResponse
    {
        $userId = Auth::id();
        $asignacion = $pedido->asignacion;
        if (! $asignacion || $asignacion->repartidor_id != $userId) {
            return redirect()->back()->with('error', 'No autorizado.');
        }

        $pedido->estado = 'por_recoger';
        $pedido->save();

        $asignacion->estado = 'por_recoger';
        $asignacion->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pedido marcado como Por recoger.', 'estado' => 'por_recoger']);
        }

        return redirect()->route('repartidor.panel')->with('success', 'Pedido marcado como "Por recoger".');
    }

    /**
     * Marcar pedido como 'en camino' (ya fue recogido y está en ruta hacia el cliente).
     */
    public function marcarEnCamino(Request $request, Pedido $pedido): RedirectResponse
    {
        $userId = Auth::id();
        $asignacion = $pedido->asignacion;
        if (! $asignacion || $asignacion->repartidor_id != $userId) {
            return redirect()->back()->with('error', 'No autorizado.');
        }

        $pedido->estado = 'en_camino';
        $pedido->save();

        $asignacion->estado = 'en_ruta';
        $asignacion->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Pedido marcado como En camino.', 'estado' => 'en_camino']);
        }

        return redirect()->route('repartidor.panel')->with('success', 'Pedido marcado como "En camino".');
    }

    /**
     * Alterna el estado de disponibilidad del repartidor autenticado.
     */
    public function toggleDisponible(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->back()->with('error', 'Usuario no autenticado.');
        }

        $perfil = $user->repartidor;
        if (! $perfil) {
            return redirect()->back()->with('error', 'No tienes un perfil de repartidor.');
        }

        $perfil->disponible = ! (bool) $perfil->disponible;
        $perfil->save();

        $msg = $perfil->disponible ? 'Ahora estás disponible.' : 'Ahora estás no disponible.';
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'disponible' => (bool) $perfil->disponible, 'message' => $msg]);
        }

        return redirect()->route('repartidor.panel')->with('success', $msg);
    }

    /**
     * Guarda los datos del nuevo repartidor.
     */
    public function storeRegistro(Request $request)
    {
        $request->validate([
            'vehiculo' => 'required|string|max:100',
            'matricula' => 'required|string|max:20',
        ]);

        $user = Auth::user();

        // 1. Creamos el perfil en la tabla 'repartidores'
        Repartidor::create([
            'user_id' => $user->id,
            'vehiculo' => $request->vehiculo,
            'matricula' => $request->matricula,
            'disponible' => true, // Lo ponemos disponible por defecto
        ]);

        // 2. Buscamos el rol 'repartidor'
        $rolRepartidor = Rol::where('nombre', 'repartidor')->first();

        // 3. Le asignamos el nuevo rol
        if ($rolRepartidor) {
            $user->roles()->attach($rolRepartidor->id);
        }

        // 4. Redirigimos al panel de repartidor
        return redirect()->route('repartidor.panel')->with('success', '¡Felicidades! Ya eres repartidor.');
    }
}