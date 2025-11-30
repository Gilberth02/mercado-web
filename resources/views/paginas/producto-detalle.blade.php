@extends('layouts.principal')
@section('titulo', $producto->nombre . ' – Mercado Web')

@section('estilos')
<style>
    * { box-sizing: border-box; }
    
    .producto-detalle-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    
    /* Breadcrumb */
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        font-size: 0.875rem;
        margin-bottom: 2rem;
    }
    
    .breadcrumb a {
        color: #059669;
        text-decoration: none;
        transition: color 0.2s;
    }
    
    .breadcrumb a:hover {
        color: #047857;
    }
    
    /* Grid Layout */
    .detalle-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    
    .detalle-img {
        position: sticky;
        top: 2rem;
        height: fit-content;
    }
    
    .detalle-img img {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
    }
    
    /* Product Info */
    .titu-producto {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #1f2937;
        margin: 0 0 1rem 0;
        line-height: 1.2;
    }
    
    .vendedor-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #f3f4f6;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        color: #4b5563;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    
    .vendedor-badge strong {
        color: #059669;
    }
    
    .estrellas-top {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 1.125rem;
    }
    
    .estrellas-top span {
        color: #fbbf24;
        font-size: 1.25rem;
    }
    
    .precio-grande {
        font-size: 2.5rem;
        font-weight: 700;
        color: #059669;
        margin: 1rem 0;
    }
    
    .stock {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }
    
    .stock.bajo {
        color: #dc2626;
        font-weight: 600;
    }
    
    .descripcion-section {
        padding: 1.5rem 0;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }
    
    .descripcion-section h3 {
        font-size: 1.25rem;
        color: #1f2937;
        margin: 0 0 0.75rem 0;
    }
    
    .descripcion-section p {
        color: #4b5563;
        line-height: 1.6;
        margin: 0;
    }
    
    /* Add to Cart Form */
    .form-compra {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .cantidad-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .cantidad-wrapper label {
        font-weight: 600;
        color: #374151;
    }
    
    .cantidad-wrapper input {
        width: 100px;
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        text-align: center;
    }
    
    .boton-grande {
        width: 100%;
        padding: 1rem;
        font-size: 1.125rem;
        font-weight: 600;
        background: #059669;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .boton-grande:hover {
        background: #047857;
    }
    
    .boton-grande.agotado {
        background: #d1d5db;
        cursor: not-allowed;
    }
    
    /* Reviews Section */
    .zona-resenas {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .zona-resenas h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        color: #1f2937;
        margin: 0 0 1.5rem 0;
    }
    
    .form-resena {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }
    
    .form-resena h4 {
        font-size: 1.125rem;
        color: #1f2937;
        margin: 0 0 1rem 0;
    }
    
    .fila-resena {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .fila-resena select {
        flex: 1;
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
    }
    
    .fila-resena button {
        padding: 0.75rem 1.5rem;
        background: #059669;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    .fila-resena button:hover {
        background: #047857;
    }
    
    .form-resena textarea {
        width: 100%;
        padding: 0.875rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: inherit;
        resize: vertical;
        min-height: 100px;
    }
    
    .form-resena textarea:focus {
        outline: none;
        border-color: #059669;
    }
    
    /* Comments List */
    .lista-comentarios {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .comentario-item {
        padding: 1.5rem;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 4px solid #059669;
    }
    
    .header-comentario {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
    }
    
    .header-comentario strong {
        color: #1f2937;
        font-size: 1rem;
    }
    
    .header-comentario .estrellas {
        color: #fbbf24;
        font-size: 1rem;
    }
    
    .header-comentario small {
        color: #9ca3af;
        font-size: 0.875rem;
        margin-left: auto;
    }
    
    .comentario-item p {
        color: #4b5563;
        line-height: 1.6;
        margin: 0;
    }
    
    .empty-reviews {
        text-align: center;
        padding: 3rem;
        color: #9ca3af;
    }
    
    .empty-reviews i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .detalle-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 1.5rem;
        }
        
        .detalle-img {
            position: static;
        }
        
        .titu-producto {
            font-size: 1.5rem;
        }
        
        .precio-grande {
            font-size: 2rem;
        }
        
        .fila-resena {
            flex-direction: column;
        }
        
        .fila-resena button {
            width: 100%;
        }
    }
</style>
@endsection

@section('contenido')
<div class="producto-detalle-container">
    
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('tienda.index') }}"><i class="fas fa-store"></i> Tienda</a>
        <span><i class="fas fa-chevron-right"></i></span>
        <span>{{ $producto->nombre }}</span>
    </div>

    {{-- Product Details Grid --}}
    <div class="detalle-grid">
        
        {{-- Image Column --}}
        <div class="detalle-img">
            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
        </div>

        {{-- Info Column --}}
        <div class="detalle-info">
            <h1 class="titu-producto">{{ $producto->nombre }}</h1>
            
            <div class="vendedor-badge">
                <i class="fas fa-store"></i>
                Vendido por: <strong>{{ $producto->vendedor->nombre_negocio }}</strong>
            </div>

            <div class="estrellas-top">
                <span>★</span> 
                <strong>{{ number_format($producto->promedioCalificacion(), 1) }}</strong> / 5
                <span style="color: #9ca3af; font-size: 0.875rem;">({{ $producto->resenas->count() }} {{ $producto->resenas->count() === 1 ? 'opinión' : 'opiniones' }})</span>
            </div>

            <div class="precio-grande">S/ {{ number_format($producto->precio, 2) }}</div>

            <p class="stock {{ $producto->stock < 10 ? 'bajo' : '' }}">
                <i class="fas fa-box"></i> 
                @if($producto->stock > 0)
                    {{ $producto->stock }} unidades disponibles
                    @if($producto->stock < 10)
                        <span style="color: #dc2626;">¡Últimas unidades!</span>
                    @endif
                @else
                    <span style="color: #dc2626;">Sin stock</span>
                @endif
            </p>

            <div class="descripcion-section">
                <h3>Descripción</h3>
                <p>{{ $producto->descripcion }}</p>
            </div>

            {{-- Add to Cart Form --}}
            <form action="{{ route('cart.add', $producto) }}" method="POST" class="form-compra ajax-add-cart">
                @csrf
                
                <div class="cantidad-wrapper">
                    <label for="quantity">Cantidad:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $producto->stock }}" required>
                </div>
                
                @if($producto->stock > 0)
                    <button type="submit" class="boton-grande">
                        <i class="fas fa-shopping-cart"></i>
                        Añadir al Carrito
                    </button>
                @else
                    <button type="button" class="boton-grande agotado" disabled>
                        <i class="fas fa-times-circle"></i>
                        Agotado
                    </button>
                @endif
            </form>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="zona-resenas">
        <h2><i class="fas fa-comments"></i> Opiniones de los clientes</h2>

        {{-- Review Form (Only for authenticated users) --}}
        @auth
            <form action="{{ route('resenas.store', $producto) }}" method="POST" class="form-resena ajax-review-form">
                @csrf
                <h4>Deja tu opinión</h4>
                <div class="fila-resena">
                    <select name="puntuacion" required>
                        <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                        <option value="4">⭐⭐⭐⭐ Muy bueno</option>
                        <option value="3">⭐⭐⭐ Regular</option>
                        <option value="2">⭐⭐ Malo</option>
                        <option value="1">⭐ Pésimo</option>
                    </select>
                    <button type="submit">
                        <i class="fas fa-paper-plane"></i> Publicar
                    </button>
                </div>
                <textarea name="comentario" placeholder="¿Qué te pareció el producto? Comparte tu experiencia..." required></textarea>
            </form>
        @else
            <div style="background: #fef3c7; color: #92400e; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                <i class="fas fa-info-circle"></i>
                Debes <a href="{{ route('login') }}" style="color: #059669; font-weight: 600;">iniciar sesión</a> para dejar una opinión.
            </div>
        @endauth

        {{-- Comments List --}}
        <div class="lista-comentarios" id="lista-comentarios">
            @forelse($producto->resenas as $resena)
                <div class="comentario-item">
                    <div class="header-comentario">
                        <i class="fas fa-user-circle" style="font-size: 1.5rem; color: #9ca3af;"></i>
                        <strong>{{ $resena->user->name }}</strong>
                        <span class="estrellas">{{ str_repeat('★', $resena->puntuacion) }}</span>
                        <small>{{ $resena->created_at->format('d/m/Y') }}</small>
                    </div>
                    <p>{{ $resena->comentario }}</p>
                </div>
            @empty
                <div class="empty-reviews">
                    <i class="fas fa-comment-slash"></i>
                    <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">Aún no hay opiniones</p>
                    <p>¡Sé el primero en opinar sobre este producto!</p>
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection

@section('estilos')
<style>
    /* Responsive móvil para detalle de producto */
    @media (max-width: 768px) {
        .cantidad-wrapper { display: flex; align-items: center; gap: 0.75rem; }
        .form-compra .boton-grande { width: 100%; margin-top: 0.75rem; }
        .zona-resenas { padding: 0 0.75rem; }
        .comentario-item { padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 0.75rem; }
    }
    @media (max-width: 420px) {
        .cantidad-wrapper label { display: none; }
        #quantity { width: 100%; }
    }
</style>
@endsection

@section('scripts')
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

    setTimeout(() => alert.remove(), 3000);
}

document.addEventListener('DOMContentLoaded', function(){
    // AJAX para añadir al carrito
    const cartForm = document.querySelector('.ajax-add-cart');
    if (cartForm) {
        cartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button[type="submit"]');
            const originalBtnText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('¡Producto añadido al carrito!');
                    
                    // Animar el botón
                    btn.style.background = '#10b981';
                    btn.innerHTML = '<i class="fas fa-check"></i> ¡Añadido!';
                    
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.style.background = '';
                        btn.innerHTML = originalBtnText;
                    }, 2000);
                } else {
                    showMessage(data.message || 'Error al añadir producto', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error al procesar la solicitud', 'error');
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            });
        });
    }

    // AJAX para enviar reseña
    const reviewForm = document.querySelector('.ajax-review-form');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button[type="submit"]');
            const originalBtnText = btn.innerHTML;
            const textarea = this.querySelector('textarea[name="comentario"]');
            const select = this.querySelector('select[name="puntuacion"]');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('¡Reseña publicada exitosamente!');
                    
                    // Limpiar formulario
                    textarea.value = '';
                    select.value = '5';
                    
                    // Agregar nueva reseña a la lista
                    const listaComentarios = document.getElementById('lista-comentarios');
                    const emptyState = listaComentarios.querySelector('.empty-reviews');
                    
                    if (emptyState) {
                        emptyState.remove();
                    }
                    
                    if (data.resena) {
                        const stars = '★'.repeat(data.resena.puntuacion);
                        const newComment = document.createElement('div');
                        newComment.className = 'comentario-item';
                        newComment.style.background = '#d1fae5';
                        newComment.innerHTML = `
                            <div class="header-comentario">
                                <i class="fas fa-user-circle" style="font-size: 1.5rem; color: #9ca3af;"></i>
                                <strong>${data.resena.user_name}</strong>
                                <span class="estrellas">${stars}</span>
                                <small>${data.resena.fecha}</small>
                            </div>
                            <p>${data.resena.comentario}</p>
                        `;
                        
                        listaComentarios.insertBefore(newComment, listaComentarios.firstChild);
                        
                        // Remover highlight después de 3 segundos
                        setTimeout(() => {
                            newComment.style.transition = 'background 0.5s ease';
                            newComment.style.background = '';
                        }, 3000);
                    }
                    
                    btn.disabled = false;
                    btn.innerHTML = originalBtnText;
                } else {
                    showMessage(data.message || 'Error al publicar reseña', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error al procesar la solicitud', 'error');
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            });
        });
    }
});
</script>
@endsection