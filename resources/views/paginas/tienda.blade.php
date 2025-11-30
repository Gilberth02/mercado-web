@extends('layouts.principal')
@section('titulo','Tienda - Mercado Web')

@section('estilos')
<style>
    .tienda-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .tienda-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .tienda-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    .tienda-header p {
        font-size: 1.125rem;
        color: #6b7280;
    }
    .filtros-container {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    .filtros-toggle {
        display: none;
        width: 100%;
        background: #059669;
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-weight: 700;
        display: none;
    }
    .filtros-toggle i { margin-right: 8px; }
    .filtros-form {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto;
        gap: 1rem;
        align-items: end;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .form-input, .form-select {
        padding: 0.625rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: border-color 0.2s;
    }
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    .btn-buscar {
        background: #059669;
        color: white;
        border: none;
        padding: 0.625rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .btn-buscar:hover {
        background: #047857;
    }
    .btn-limpiar {
        background: #6b7280;
        color: white;
        border: none;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-limpiar:hover {
        background: #4b5563;
    }
    .resultados-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        color: #6b7280;
        font-size: 0.875rem;
    }
    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .producto-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .producto-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }
    .producto-imagen {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f3f4f6;
    }
    .producto-imagen img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .producto-card:hover .producto-imagen img {
        transform: scale(1.05);
    }
    .producto-info {
        padding: 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .producto-nombre {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-decoration: none;
    }
    .producto-nombre:hover {
        color: #059669;
    }
    .producto-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }
    .estrellas {
        display: flex;
        gap: 2px;
    }
    .estrella {
        color: #fbbf24;
        font-size: 1rem;
    }
    .estrella.vacia {
        color: #d1d5db;
    }
    .rating-text {
        font-size: 0.875rem;
        color: #6b7280;
    }
    .producto-precio {
        font-size: 1.25rem;
        font-weight: 700;
        color: #059669;
        margin-bottom: 0.5rem;
    }
    .producto-vendedor {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .producto-vendedor i {
        color: #059669;
    }
    .btn-carrito {
        width: 100%;
        background: #059669;
        color: white;
        border: none;
        padding: 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background 0.2s ease;
        margin-top: auto;
    }
    .btn-carrito:hover {
        background: #047857;
    }
    .no-productos {
        text-align: center;
        padding: 4rem 1rem;
        color: #6b7280;
    }
    .no-productos i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #d1d5db;
    }
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }
    .pagination a, .pagination span {
        padding: 0.5rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        text-decoration: none;
        color: #374151;
        transition: all 0.2s;
    }
    .pagination a:hover {
        background: #f3f4f6;
        border-color: #059669;
    }
    .pagination .active {
        background: #059669;
        color: white;
        border-color: #059669;
    }
    .pagination .disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    @media (max-width: 1024px) {
        .filtros-form {
            grid-template-columns: 1fr 1fr;
        }
        .form-group:first-child {
            grid-column: 1 / -1;
        }
    }
    @media (max-width: 768px) {
        .filtros-form {
            grid-template-columns: 1fr;
        }
        .filtros-toggle { display: block; }
        .filtros-container { padding: 1rem; }
        .filtros-cuerpo { display: none; margin-top: 0.75rem; }
        .filtros-cuerpo.abierto { display: block; }
        .productos-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.5rem;
        }
        .tienda-header h1 {
            font-size: 2rem;
        }
        .resultados-info {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
    }

    /* Mobile-first adjustments */
    @media (max-width: 640px) {
        .tienda-container {
            padding: 1rem 0.75rem;
        }
        .tienda-header h1 {
            font-size: 1.75rem;
        }
        .tienda-header p {
            font-size: 1rem;
        }
        .filtros-container {
            padding: 1rem;
        }
        /* Convert filters to stacked accordion style */
        .filtros-form {
            display: block;
        }
        .form-group {
            margin-bottom: 0.75rem;
        }
        .form-label {
            margin-bottom: 0.4rem;
        }
        .form-input, .form-select {
            font-size: 0.9rem;
            padding: 0.6rem 0.7rem;
        }
        .btn-buscar, .btn-limpiar {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }
        .resultados-info {
            font-size: 0.8rem;
            gap: 0.25rem;
        }
        .productos-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        /* When very small widths, go single column */
        @media (max-width: 380px) {
            .productos-grid { grid-template-columns: 1fr; }
        }
        .producto-imagen {
            height: 150px;
        }
        .producto-info {
            padding: 0.75rem;
        }
        .producto-nombre {
            font-size: 0.95rem;
        }
        .producto-precio {
            font-size: 1.1rem;
        }
        .producto-vendedor {
            font-size: 0.8rem;
        }
        .btn-carrito {
            padding: 0.6rem;
            font-size: 0.85rem;
        }
        .pagination {
            gap: 0.35rem;
        }
        .pagination a, .pagination span {
            padding: 0.45rem 0.6rem;
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('contenido')
<div class="tienda-container">
    <div class="tienda-header">
        <h1>Tienda</h1>
        <p>Explora todos nuestros productos locales</p>
    </div>

    {{-- Filtros --}}
    <div class="filtros-container">
        <button type="button" id="btn-toggle-filtros" class="filtros-toggle"><i class="fas fa-filter"></i> Filtros</button>
        <div class="filtros-cuerpo abierto">
        <form action="{{ route('tienda.index') }}" method="GET" class="filtros-form">
            <div class="form-group">
                <label class="form-label">Buscar</label>
                <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-input" placeholder="Buscar productos...">
            </div>
            
            <div class="form-group">
                <label class="form-label">Categoría</label>
                <select name="categoria" class="form-select">
                    <option value="">Todas</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Ordenar por</label>
                <select name="orden" class="form-select">
                    <option value="reciente" {{ request('orden') == 'reciente' ? 'selected' : '' }}>Más reciente</option>
                    <option value="precio_asc" {{ request('orden') == 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                    <option value="precio_desc" {{ request('orden') == 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                    <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label" style="opacity: 0;">Acciones</label>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn-buscar">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                    @if(request()->hasAny(['buscar', 'categoria', 'orden']))
                        <a href="{{ route('tienda.index') }}" class="btn-limpiar">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
        </div>
    </div>

    {{-- Resultados --}}
    <div class="resultados-info">
        <span>Mostrando {{ $productos->firstItem() ?? 0 }} - {{ $productos->lastItem() ?? 0 }} de {{ $productos->total() }} productos</span>
        @if(request('buscar'))
            <span>Resultados para: <strong>"{{ request('buscar') }}"</strong></span>
        @endif
    </div>

    <div class="productos-grid">
        @forelse ($productos as $producto)
            <div class="producto-card">
                <a href="{{ route('producto.show', $producto) }}" class="producto-imagen">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f3f4f6;color:#9ca3af;">
                            <i class="fas fa-image" style="font-size:3rem;"></i>
                        </div>
                    @endif
                </a>
                
                <div class="producto-info">
                    <a href="{{ route('producto.show', $producto) }}" class="producto-nombre">
                        {{ $producto->nombre }}
                    </a>
                    
                    {{-- Calificación con estrellas --}}
                    <div class="producto-rating">
                        @php
                            $promedio = $producto->promedioCalificacion();
                            $estrellas = round($promedio);
                        @endphp
                        <div class="estrellas">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star estrella {{ $i <= $estrellas ? '' : 'vacia' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-text">
                            @if($producto->resenas->count() > 0)
                                {{ number_format($promedio, 1) }} ({{ $producto->resenas->count() }})
                            @else
                                Sin reseñas
                            @endif
                        </span>
                    </div>
                    
                    <div class="producto-precio">S/ {{ number_format($producto->precio, 2) }}</div>
                    
                    <div class="producto-vendedor">
                        <i class="fas fa-store"></i>
                        <span>{{ optional($producto->vendedor)->nombre_negocio ?? 'Desconocido' }}</span>
                    </div>
                    
                    <form action="{{ route('cart.add', $producto) }}" method="POST" class="ajax-add-cart">
                        @csrf
                        <button type="submit" class="btn-carrito">
                            <i class="fas fa-shopping-cart"></i> Añadir al Carrito
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="no-productos" style="grid-column: 1 / -1;">
                <i class="fas fa-store-slash"></i>
                <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No hay productos disponibles</p>
                @if(request('buscar'))
                    <p>No encontramos productos que coincidan con "{{ request('buscar') }}"</p>
                @else
                    <p>Vuelve pronto para ver nuestros productos locales</p>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    @if($productos->hasPages())
        <div class="pagination">
            {{-- Botón anterior --}}
            @if ($productos->onFirstPage())
                <span class="disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $productos->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
            @endif

            {{-- Números de página --}}
            @foreach ($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                @if ($page == $productos->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Botón siguiente --}}
            @if ($productos->hasMorePages())
                <a href="{{ $productos->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const toggleBtn = document.getElementById('btn-toggle-filtros');
    const body = document.querySelector('.filtros-cuerpo');
    if (toggleBtn && body) {
        toggleBtn.addEventListener('click', function(){
            body.classList.toggle('abierto');
            // Cambiar icono y texto
            if (body.classList.contains('abierto')) {
                toggleBtn.innerHTML = '<i class="fas fa-filter"></i> Filtros';
            } else {
                toggleBtn.innerHTML = '<i class="fas fa-filter"></i> Mostrar filtros';
            }
        });
    }
});
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
    document.querySelectorAll('.ajax-add-cart').forEach(form => {
        form.addEventListener('submit', function(e) {
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
    });
});
</script>
@endsection