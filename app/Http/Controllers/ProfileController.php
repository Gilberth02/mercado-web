<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $estadisticas = [];

        // Obtener roles del usuario
        $roles = $user->roles->pluck('nombre')->toArray();

        // Estadísticas para Vendedor
        if (in_array('vendedor', $roles) && $user->vendedor) {
            $vendedorId = $user->vendedor->user_id;
            
            // Productos publicados
            $estadisticas['productos_publicados'] = \App\Models\Producto::where('vendedor_id', $vendedorId)->count();
            
            // Productos vendidos (cantidad total de productos en pedidos entregados)
            $estadisticas['productos_vendidos'] = \App\Models\DetallePedido::whereHas('producto', function($q) use ($vendedorId) {
                $q->where('vendedor_id', $vendedorId);
            })->whereHas('pedido', function($q) {
                $q->where('estado', 'entregado');
            })->sum('cantidad');
        }

        // Estadísticas para Repartidor
        if (in_array('repartidor', $roles) && $user->repartidor) {
            $repartidorId = $user->repartidor->user_id;
            
            // Pedidos entregados
            $estadisticas['pedidos_entregados'] = \App\Models\AsignacionEnvio::where('repartidor_id', $repartidorId)
                ->where('estado', 'entregado')
                ->count();
        }

        // Estadísticas para Cliente (todos tienen este rol)
        if (in_array('cliente', $roles)) {
            // Pedidos realizados como comprador
            $estadisticas['pedidos_comprador'] = \App\Models\Pedido::where('cliente_id', $user->id)->count();
            
            // Pedidos entregados como comprador
            $estadisticas['pedidos_entregados_comprador'] = \App\Models\Pedido::where('cliente_id', $user->id)
                ->where('estado', 'entregado')
                ->count();
        }

        return view('profile.edit', [
            'user' => $user,
            'estadisticas' => $estadisticas,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Manejar eliminación de foto
        if ($request->input('remove_photo') == '1') {
            if ($user->profile_photo_path && !filter_var($user->profile_photo_path, FILTER_VALIDATE_URL)) {
                // Eliminar archivo físico si existe
                \Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = null;
        }
        
        // Manejar subida de nueva foto
        if ($request->hasFile('profile_photo')) {
            $request->validate([
                'profile_photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Eliminar foto anterior si existe y no es URL externa
            if ($user->profile_photo_path && !filter_var($user->profile_photo_path, FILTER_VALIDATE_URL)) {
                \Storage::disk('public')->delete($user->profile_photo_path);
            }
            
            // Guardar nueva foto
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
