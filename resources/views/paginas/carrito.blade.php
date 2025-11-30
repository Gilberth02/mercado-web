@extends('layouts.principal')
@section('titulo','Mi Carrito')

@section('estilos')
<style>
    * { box-sizing: border-box; }
    
    .carrito-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    
    .carrito-header {
        margin-bottom: 2rem;
    }
    
    .carrito-titulo {
        font-size: 2rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-family: 'Playfair Display', serif;
    }
    
    .carrito-subtitulo {
        color: #6b7280;
        font-size: 0.95rem;
    }

    /* Rating Button */
    .btn-calificar {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 2px 4px rgba(5, 150, 105, 0.2);
    }

    .btn-calificar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }

    .btn-calificar:active {
        transform: translateY(0);
    }

    .calificado-badge {
        color: #059669;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Alert */
    .alerta-exito {
        color: #059669;
        background: #d1fae5;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #059669;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Section Card */
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .section-titulo {
        font-size: 1.5rem;
        color: #1f2937;
        margin: 0;
        font-family: 'Playfair Display', serif;
    }

    .items-count {
        color: #6b7280;
        font-size: 0.95rem;
    }

    /* Table Wrapper */
    .tabla-wrapper {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 600px;
    }
    
        /* Mobile responsive for carrito */
        @media (max-width: 768px) {
            .carrito-container { padding: 0 1rem; }
            .tabla-wrapper { max-height: none; }
            .tabla-moderna thead { display: none; }
            .tabla-moderna tr { display: grid; grid-template-columns: 80px 1fr; gap: 0.5rem; padding: 0.75rem 0; border-bottom: 1px solid #e5e7eb; }
            .tabla-moderna td { border: none; padding: 0.25rem 0; }
            .img-carrito { width: 74px; height: 74px; border-radius: 8px; object-fit: cover; }
            .cantidad-input { width: 100%; }
            .btn-sm { padding: 0.5rem 0.75rem; font-size: 0.8rem; }
            .total-section { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .acciones-carrito { display: flex; gap: 0.5rem; flex-wrap: wrap; }
            .btn-vaciar, .btn-pagar { width: 100%; text-align: center; }
        }

        @media (max-width: 420px) {
            .carrito-titulo { font-size: 1.5rem; }
            .btn-vaciar, .btn-pagar { padding: 0.6rem 0.9rem; }
        }
    
    .tabla-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .tabla-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .tabla-wrapper::-webkit-scrollbar-thumb {
        background: #059669;
        border-radius: 4px;
    }

    /* Modern Table */
    .tabla-moderna {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .tabla-moderna thead {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f9fafb;
    }
    
    .tabla-moderna th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }
    
    .tabla-moderna td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        color: #4b5563;
    }
    
    .tabla-moderna tbody tr {
        transition: background-color 0.15s;
    }
    
    .tabla-moderna tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .img-carrito {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }

    /* Quantity Form */
    .cantidad-form {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    .cantidad-input {
        width: 70px;
        padding: 0.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        text-align: center;
        font-weight: 600;
    }
    
    .cantidad-input:focus {
        outline: none;
        border-color: #059669;
    }

    /* Buttons */
    .btn-sm {
        padding: 0.5rem 0.875rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
    }
    
    .btn-actualizar {
        background: #3b82f6;
        color: white;
    }
    
    .btn-actualizar:hover {
        background: #2563eb;
    }
    
    .btn-eliminar {
        background: #dc2626;
        color: white;
    }
    
    .btn-eliminar:hover {
        background: #b91c1c;
    }

    /* Total Section */
    .total-section {
        padding: 1.5rem;
        background: #f9fafb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .total-carrito {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1f2937;
    }
    
    .acciones-carrito {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .btn-vaciar {
        background: #6b7280;
        color: white;
        padding: 0.75rem 1.25rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .btn-vaciar:hover {
        background: #4b5563;
    }
    
    .btn-pagar {
        background: #059669;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-pagar:hover {
        background: #047857;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .badge.pendiente {
        background: #fef3c7;
        color: #92400e;
    }
    
    .badge.entregado {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge.en-camino {
        background: #dbeafe;
        color: #1e40af;
    }

    .repartidor-info {
        background: #f9fafb;
        padding: 0.75rem;
        border-radius: 6px;
        margin-top: 0.5rem;
    }
    
    .repartidor-nombre {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }
    
    .repartidor-telefono {
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .btn-contactar {
        background: #059669;
        color: white;
        padding: 0.5rem 0.875rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        text-decoration: none;
        display: inline-block;
        margin-top: 0.5rem;
    }
    
    .btn-contactar:hover {
        background: #047857;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .carrito-container {
            padding: 0 1rem;
        }
        
        .carrito-titulo {
            font-size: 1.5rem;
        }
        
        .tabla-wrapper {
            max-height: 400px;
        }
        
        .img-carrito {
            width: 60px;
            height: 60px;
        }
        
        .total-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .total-carrito {
            text-align: center;
        }
        
        .acciones-carrito {
            flex-direction: column;
        }
        
        .btn-vaciar, .btn-pagar {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('contenido')

<div class="carrito-container">
    <div class="carrito-header">
        <h1 class="carrito-titulo">Mi Carrito de Compras</h1>
        <p class="carrito-subtitulo">Revisa y gestiona los productos que deseas comprar</p>
    </div>

    @if(session('success'))
    <div class="alerta-exito">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Carrito de compras --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Productos en el Carrito</h2>
            <span class="items-count">{{ count($cart) }} {{ count($cart) === 1 ? 'artículo' : 'artículos' }}</span>
        </div>

        @if(count($cart) > 0)
        <div class="tabla-wrapper">
            <table class="tabla-moderna">
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
                    @foreach ($productos as $producto)
                    <tr data-product-id="{{ $producto->id }}">
                        <td>
                            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                 alt="{{ $producto->nombre }}" 
                                 class="img-carrito">
                        </td>
                        <td><strong>{{ $producto->nombre }}</strong></td>
                        <td><strong>S/ {{ number_format($producto->precio, 2) }}</strong></td>
                        <td>
                            <form action="{{ route('cart.update', $producto) }}" method="POST" class="cantidad-form ajax-cart-form">
                                @csrf
                                @method('PATCH')
                                <input type="number" 
                                       name="quantity" 
                                       value="{{ $cart[$producto->id]['quantity'] }}" 
                                       min="0" 
                                       max="{{ $producto->stock ?? 999 }}"
                                       class="cantidad-input">
                                <button type="submit" class="btn-sm btn-actualizar">
                                    <i class="fas fa-sync-alt"></i> Actualizar
                                </button>
                            </form>
                        </td>
                        <td class="subtotal" data-subtotal="{{ $producto->precio * $cart[$producto->id]['quantity'] }}">
                            <strong>S/ {{ number_format($producto->precio * $cart[$producto->id]['quantity'], 2) }}</strong>
                        </td>
                        <td>
                            <form action="{{ route('cart.remove', $producto) }}" method="POST" class="ajax-cart-form" style="display:inline">
                                @csrf
                                <button type="submit" class="btn-sm btn-eliminar">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="total-section">
            <div class="total-carrito" id="total-carrito">
                Total: S/ {{ number_format($total, 2) }}
            </div>
            <div class="acciones-carrito">
                <form action="{{ route('cart.clear') }}" method="POST" class="ajax-cart-form" style="display:inline">
                    @csrf
                    <button type="submit" class="btn-vaciar" onclick="return confirm('¿Estás seguro de vaciar todo el carrito?')">
                        <i class="fas fa-trash-alt"></i> Vaciar carrito
                    </button>
                </form>
                <a href="{{ route('cart.checkout.form') }}" class="btn-pagar">
                    <i class="fas fa-credit-card"></i> Proceder al Pago
                </a>
            </div>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-shopping-cart"></i>
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">Tu carrito está vacío</p>
            <p>Explora nuestra <a href="{{ route('tienda.index') }}" style="color: #059669; font-weight: 600;">tienda</a> y añade productos</p>
        </div>
        @endif
    </div>
    {{-- Mis pedidos --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Mis Pedidos</h2>
            @if(isset($pedidos))
            <span class="items-count">{{ $pedidos->count() }} {{ $pedidos->count() === 1 ? 'pedido' : 'pedidos' }}</span>
            @endif
        </div>

        @if(isset($pedidos) && $pedidos->count() > 0)
        <div class="tabla-wrapper">
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Repartidor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    <tr>
                        <td><strong>#{{ sprintf('%06d', $pedido->id) }}</strong></td>
                        <td>
                            <ul style="list-style:none;padding-left:0;margin:0">
                                @foreach($pedido->detalles as $det)
                                <li style="padding: 0.25rem 0;">
                                    {{ optional($det->producto)->nombre ?? 'Producto eliminado' }} × {{ $det->cantidad }}
                                </li>
                                @endforeach
                            </ul>
                        </td>
                        <td><strong>S/ {{ number_format($pedido->total, 2) }}</strong></td>
                        <td>
                            @if($pedido->asignacion && $pedido->asignacion->repartidor)
                                @php
                                    $repUser = optional($pedido->asignacion->repartidor->user);
                                    $repPhone = $repUser->telefono ?? null;
                                    $telHref = $repPhone ? 'tel:+51' . preg_replace('/\s+/', '', $repPhone) : null;
                                @endphp
                                <div class="repartidor-info">
                                    <div class="repartidor-nombre">
                                        <i class="fas fa-user-circle"></i> {{ $repUser->name ?? 'Repartidor' }}
                                    </div>
                                    @if($repPhone)
                                        <div class="repartidor-telefono">
                                            <i class="fas fa-phone"></i> {{ $repPhone }}
                                        </div>
                                        <a href="{{ $telHref }}" class="btn-contactar">
                                            <i class="fas fa-phone-alt"></i> Contactar
                                        </a>
                                    @else
                                        <div class="repartidor-telefono">No tiene teléfono registrado</div>
                                    @endif
                                </div>
                            @else
                                <span class="badge pendiente">Aún no asignado</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $estadoClass = 'pendiente';
                                if ($pedido->estado === 'entregado') $estadoClass = 'entregado';
                                elseif (in_array($pedido->estado, ['en_camino', 'por_recoger', 'asignado'])) $estadoClass = 'en-camino';
                            @endphp
                            <span class="badge {{ $estadoClass }}">
                                {{ ucfirst(str_replace('_', ' ', $pedido->estado)) }}
                            </span>
                        </td>
                        <td>
                            @if($pedido->estado === 'entregado' && $pedido->asignacion && $pedido->asignacion->repartidor)
                                @php
                                    $yaCalificado = \App\Models\ResenaRepartidor::where('user_id', Auth::id())
                                        ->where('repartidor_id', $pedido->asignacion->repartidor_id)
                                        ->where('pedido_id', $pedido->id)
                                        ->exists();
                                @endphp
                                @if($yaCalificado)
                                    <span style="color: #059669; font-size: 0.875rem;">
                                        <i class="fas fa-check-circle"></i> Calificado
                                    </span>
                                @else
                                    <button class="btn-calificar" onclick="abrirModalCalificacion({{ $pedido->id }}, {{ $pedido->asignacion->repartidor_id }}, '{{ $pedido->asignacion->repartidor->user->name ?? 'Repartidor' }}')">
                                        <i class="fas fa-star"></i> Calificar
                                    </button>
                                @endif
                            @else
                                <span style="color: #9ca3af; font-size: 0.875rem;">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No tienes pedidos aún</p>
            <p>Tus pedidos aparecerán aquí después de realizar una compra</p>
        </div>
        @endif
    </div>

</div>
<script>
function showMessage(message, type = 'success') {
    const existingAlert = document.querySelector('.alerta-ajax');
    if (existingAlert) existingAlert.remove();

    const alert = document.createElement('div');
    alert.className = `alerta-ajax ${type}`;
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        animation: slideIn 0.3s ease;
        ${type === 'success' ? 'background: #d1fae5; color: #059669; border-left: 4px solid #059669;' : 'background: #fee2e2; color: #dc2626; border-left: 4px solid #dc2626;'}
    `;
    alert.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(alert);

    setTimeout(() => alert.remove(), 4000);
}

document.addEventListener('DOMContentLoaded', function(){
    // AJAX para formularios del carrito
    document.querySelectorAll('.ajax-cart-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button[type="submit"]');
            const originalBtnText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const formData = new FormData(this);
            const actionUrl = this.action;
            const isUpdate = this.classList.contains('cantidad-form');
            const isClear = actionUrl.includes('clear');

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    showMessage(data.message || 'Operación exitosa');
                    
                    if (isUpdate) {
                        // Actualizar subtotal y total
                        const row = this.closest('tr');
                        const subtotalCell = row.querySelector('.subtotal strong');
                        const quantityInput = this.querySelector('input[name="quantity"]');
                        
                        if (data.quantity == 0 || quantityInput.value == 0) {
                            // Eliminar fila con animación
                            row.style.transition = 'opacity 0.3s ease';
                            row.style.opacity = '0';
                            setTimeout(() => row.remove(), 300);
                        } else {
                            // Actualizar subtotal
                            if (subtotalCell && data.subtotal !== undefined) {
                                subtotalCell.textContent = 'S/ ' + Number(data.subtotal).toFixed(2);
                            }
                        }
                        
                        // Actualizar total
                        if (data.total !== undefined) {
                            const totalEl = document.getElementById('total-carrito');
                            if (totalEl) totalEl.textContent = 'Total: S/ ' + Number(data.total).toFixed(2);
                        }
                        
                        btn.disabled = false;
                        btn.innerHTML = originalBtnText;
                    } else if (isClear) {
                        // Vaciar carrito - recargar después de mostrar mensaje
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        // Eliminar item - remover fila
                        const row = this.closest('tr');
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        
                        setTimeout(() => {
                            row.remove();
                            
                            // Actualizar total
                            if (data.total !== undefined) {
                                const totalEl = document.getElementById('total-carrito');
                                if (totalEl) totalEl.textContent = 'Total: S/ ' + Number(data.total).toFixed(2);
                            }
                            
                            // Si no quedan items, recargar para mostrar estado vacío
                            const tbody = document.querySelector('.tabla-moderna tbody');
                            if (tbody && tbody.children.length === 0) {
                                setTimeout(() => window.location.reload(), 500);
                            }
                        }, 300);
                    }
                } else {
                    showMessage(data.message || 'Error en la operación', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalBtnText;
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('Error al procesar la solicitud', 'error');
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            }
        });
    });
});

// Rating modal functions
let currentRating = 0;
function abrirModalCalificacion(pedidoId, repartidorId, nombreRepartidor) {
    document.getElementById('modal-rating-pedido').value = pedidoId;
    document.getElementById('modal-rating-repartidor').value = repartidorId;
    document.getElementById('modal-rating-nombre').textContent = nombreRepartidor;
    document.getElementById('modal-rating-comentario').value = '';
    currentRating = 0;
    actualizarEstrellas(0);
    document.getElementById('modalCalificacion').style.display = 'flex';
}

function cerrarModalCalificacion() {
    document.getElementById('modalCalificacion').style.display = 'none';
}

function seleccionarEstrella(puntuacion) {
    currentRating = puntuacion;
    actualizarEstrellas(puntuacion);
}

function actualizarEstrellas(puntuacion) {
    for (let i = 1; i <= 5; i++) {
        const estrella = document.getElementById('estrella-' + i);
        if (i <= puntuacion) {
            estrella.classList.remove('far');
            estrella.classList.add('fas');
            estrella.style.color = '#fbbf24';
        } else {
            estrella.classList.remove('fas');
            estrella.classList.add('far');
            estrella.style.color = '#d1d5db';
        }
    }
}

function enviarCalificacion() {
    if (currentRating === 0) {
        alert('Por favor selecciona una calificación');
        return;
    }
    const pedidoId = document.getElementById('modal-rating-pedido').value;
    const repartidorId = document.getElementById('modal-rating-repartidor').value;
    const comentario = document.getElementById('modal-rating-comentario').value;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/repartidor/' + repartidorId + '/resena';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    const puntuacionInput = document.createElement('input');
    puntuacionInput.type = 'hidden';
    puntuacionInput.name = 'puntuacion';
    puntuacionInput.value = currentRating;
    form.appendChild(puntuacionInput);
    
    const pedidoInput = document.createElement('input');
    pedidoInput.type = 'hidden';
    pedidoInput.name = 'pedido_id';
    pedidoInput.value = pedidoId;
    form.appendChild(pedidoInput);
    
    const comentarioInput = document.createElement('input');
    comentarioInput.type = 'hidden';
    comentarioInput.name = 'comentario';
    comentarioInput.value = comentario;
    form.appendChild(comentarioInput);
    
    document.body.appendChild(form);
    form.submit();
}
</script>

{{-- Modal de Calificación --}}
<div id="modalCalificacion" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:12px; padding:2rem; max-width:500px; width:90%;">
        <h3 style="font-family:'Playfair Display',serif; font-size:1.5rem; margin-bottom:1rem; color:#1f2937;">
            Calificar Delivery
        </h3>
        <p style="color:#6b7280; margin-bottom:1.5rem;">
            ¿Cómo fue tu experiencia con <strong id="modal-rating-nombre"></strong>?
        </p>
        <div style="text-align:center; margin-bottom:1.5rem;">
            <i id="estrella-1" class="far fa-star" style="font-size:2.5rem; color:#d1d5db; cursor:pointer; margin:0 0.25rem;" onclick="seleccionarEstrella(1)"></i>
            <i id="estrella-2" class="far fa-star" style="font-size:2.5rem; color:#d1d5db; cursor:pointer; margin:0 0.25rem;" onclick="seleccionarEstrella(2)"></i>
            <i id="estrella-3" class="far fa-star" style="font-size:2.5rem; color:#d1d5db; cursor:pointer; margin:0 0.25rem;" onclick="seleccionarEstrella(3)"></i>
            <i id="estrella-4" class="far fa-star" style="font-size:2.5rem; color:#d1d5db; cursor:pointer; margin:0 0.25rem;" onclick="seleccionarEstrella(4)"></i>
            <i id="estrella-5" class="far fa-star" style="font-size:2.5rem; color:#d1d5db; cursor:pointer; margin:0 0.25rem;" onclick="seleccionarEstrella(5)"></i>
        </div>
        <textarea id="modal-rating-comentario" placeholder="Cuéntanos tu experiencia (opcional)" style="width:100%; padding:0.75rem; border:1px solid #d1d5db; border-radius:8px; min-height:100px; margin-bottom:1.5rem; font-family:inherit;"></textarea>
        <input type="hidden" id="modal-rating-pedido">
        <input type="hidden" id="modal-rating-repartidor">
        <div style="display:flex; gap:1rem; justify-content:flex-end;">
            <button onclick="cerrarModalCalificacion()" style="background:#f3f4f6; color:#374151; border:none; padding:0.75rem 1.5rem; border-radius:8px; cursor:pointer; font-weight:600;">
                Cancelar
            </button>
            <button onclick="enviarCalificacion()" style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border:none; padding:0.75rem 1.5rem; border-radius:8px; cursor:pointer; font-weight:600;">
                <i class="fas fa-paper-plane"></i> Enviar
            </button>
        </div>
    </div>
</div>
@endsection