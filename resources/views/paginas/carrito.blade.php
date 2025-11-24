@extends('layouts.principal')
@section('titulo','Mi Carrito')

@section('contenido')
<div class="contenedor">
    <h1>Mi Carrito de Compras</h1>

    {{-- Estilos para la tabla --}}
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        .img-carrito { width: 80px; height: 80px; object-fit: cover; }
        .total-carrito { text-align: right; font-size: 24px; font-weight: bold; margin-top: 20px; }
        .acciones-carrito { text-align: right; }
        .btn-eliminar { background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px; }
        .btn-pagar { background: #1ca69a; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>

    @if(session('success'))
      <div class="alerta-exito" style="color: green; background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        {{ session('success') }}
      </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr data-product-id="{{ $producto->id }}">
                    <td>
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-carrito">
                    </td>
                    <td>{{ $producto->nombre }}</td>
                    <td>S/ {{ number_format($producto->precio, 2) }}</td>
                    <td>
                        {{-- Formulario para actualizar la cantidad --}}
                        <form action="{{ route('cart.update', $producto) }}" method="POST" style="display:flex;gap:8px;align-items:center">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $cart[$producto->id]['quantity'] }}" min="0" style="width:70px;padding:6px;border:1px solid #ddd;border-radius:6px">
                            <button type="submit" style="background:#0b5ed7;color:#fff;padding:6px 10px;border-radius:6px;border:none;">Actualizar</button>
                        </form>
                    </td>
                    <td class="subtotal">
                        {{-- Calculamos el subtotal --}}
                        S/ {{ number_format($producto->precio * $cart[$producto->id]['quantity'], 2) }}
                    </td>
                    <td>
                        {{-- Botón de Eliminar --}}
                        <form action="{{ route('cart.remove', $producto) }}" method="POST" style="display:inline">
                            @csrf
                            <button type="submit" class="btn-eliminar">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        Tu carrito está vacío.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Total solo si hay productos --}}
    @if(count($cart) > 0)
        <div class="total-carrito">
            Total: S/ {{ number_format($total, 2) }}
        </div>
        <div class="acciones-carrito" style="margin-top: 20px;">
            <form action="{{ route('cart.clear') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" style="background:#dc3545;color:#fff;padding:10px 14px;border-radius:6px;border:none;margin-right:8px">Vaciar carrito</button>
            </form>
            <a href="{{ route('cart.checkout.form') }}" class="btn-pagar">Proceder al Pago</a>
        </div>
    @endif

    {{-- Mis pedidos del cliente (movido desde perfil) --}}
    <hr style="margin-top:30px;margin-bottom:20px;">
    <h2>Mis pedidos</h2>
    @if(isset($pedidos) && $pedidos->count() > 0)
        <div class="tabla_responsive">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Repartidor</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td>#{{ sprintf('%06d', $pedido->id) }}</td>
                            <td>
                                <ul style="list-style:none;padding-left:0;margin:0">
                                    @foreach($pedido->detalles as $det)
                                        <li>{{ optional($det->producto)->nombre }} x {{ $det->cantidad }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>S/ {{ number_format($pedido->total, 2) }}</td>
                            <td>
                                @if($pedido->asignacion && $pedido->asignacion->repartidor)
                                    @php
                                        $repUser = optional($pedido->asignacion->repartidor->user);
                                        $repPhone = $repUser->telefono ?? null;
                                        $telHref = $repPhone ? 'tel:' . preg_replace('/\s+/', '', $repPhone) : null;
                                    @endphp
                                    <div>{{ $repUser->name ?? 'Repartidor' }}</div>
                                    @if($repPhone)
                                        <div class="small">{{ $repPhone }}</div>
                                        <div style="margin-top:6px">
                                            <a href="{{ $telHref }}" class="boton_borde" style="padding:6px 10px;">Contactar repartidor</a>
                                        </div>
                                    @else
                                        <div class="small">No tiene teléfono registrado</div>
                                    @endif
                                @else
                                    <span class="small">Aún no asignado</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($pedido->estado) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No tienes pedidos aún.</p>
    @endif

</div>
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Interceptar formularios dentro de la tabla del carrito
    const cartTable = document.querySelector('.contenedor table');
    if (!cartTable) return;

    cartTable.addEventListener('submit', async function(e){
        const form = e.target;
        if (form.tagName.toLowerCase() !== 'form') return;
        e.preventDefault();

        const formData = new FormData(form);
        const action = form.getAttribute('action');

        try {
            const response = await fetch(action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const data = await response.json();
            if (!data || !data.success) {
                // Fallback: recargar la página si la respuesta no es la esperada
                return window.location.reload();
            }

            // Si fue una actualización de cantidad: actualizar subtotal y total
            if (form.querySelector('input[name="quantity"]')) {
                const tr = form.closest('tr');
                const subtotalCell = tr.querySelector('.subtotal');
                if (typeof data.subtotal !== 'undefined') {
                    subtotalCell.textContent = 'S/ ' + Number(data.subtotal).toFixed(2);
                }
                // Actualizar total global si viene
                if (typeof data.total !== 'undefined') {
                    const totalEl = document.querySelector('.total-carrito');
                    if (totalEl) totalEl.textContent = 'Total: S/ ' + Number(data.total).toFixed(2);
                }
                // Si cantidad es 0 el backend puede devolver que el item fue eliminado
                if (form.querySelector('input[name="quantity"]').value == 0) {
                    tr.remove();
                }
                return;
            }

            // Si fue un remove (botón eliminar) -> eliminar fila y actualizar total
            if (form.querySelector('button.btn-eliminar')) {
                const tr = form.closest('tr');
                if (tr) tr.remove();
                if (typeof data.total !== 'undefined') {
                    const totalEl = document.querySelector('.total-carrito');
                    if (totalEl) totalEl.textContent = 'Total: S/ ' + Number(data.total).toFixed(2);
                }
                return;
            }

            // Si fue vaciar carrito
            if (action && action.indexOf('{{ route("cart.clear") }}') !== -1) {
                // Remover todas las filas y mostrar mensaje vacío
                const tbody = cartTable.querySelector('tbody');
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px">Tu carrito está vacío.</td></tr>';
                const totalEl = document.querySelector('.total-carrito');
                if (totalEl) totalEl.textContent = 'Total: S/ 0.00';
                // Ocultar acciones
                const acciones = document.querySelector('.acciones-carrito');
                if (acciones) acciones.style.display = 'none';
                return;
            }

        } catch (err) {
            console.error('Error en petición carrito', err);
            // En caso de error, fallback a recargar
            window.location.reload();
        }
    });
});
</script>
@endsection