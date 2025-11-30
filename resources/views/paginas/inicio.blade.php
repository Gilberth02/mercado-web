@extends('layouts.principal')
@section('titulo','Mercado Web – Inicio')

@section('estilos')
<style>
    * { box-sizing: border-box; }
    
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        padding: 4rem 0;
        text-align: center;
    }
    
    .hero-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .hero-titulo {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .hero-subtitulo {
        font-size: 1.25rem;
        color: #6b7280;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .hero-search {
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }
    
    .hero-search input {
        width: 100%;
        padding: 1rem 3.5rem 1rem 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 50px;
        font-size: 1rem;
        transition: all 0.2s;
    }
    
    .hero-search input:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    
    .hero-search i {
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.25rem;
    }

    /* Products Section */
    .productos-section {
        max-width: 1400px;
        margin: 4rem auto;
        padding: 0 1.5rem;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .section-titulo {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #1f2937;
        font-weight: 700;
    }
    
    .ver-todos {
        color: #059669;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: gap 0.2s;
    }
    
    .ver-todos:hover {
        gap: 0.75rem;
    }

    /* Product Grid */
    .productos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
    }
    
    .producto-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    /* Responsive móvil */
    @media (max-width: 768px) {
        .hero-section { padding: 2.5rem 0; }
        .hero-titulo { font-size: 2.25rem; }
        .hero-subtitulo { font-size: 1rem; }
        .hero-search { max-width: 100%; }
        .productos-section { margin: 2rem auto; padding: 0 1rem; }
        .section-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        .productos-grid { grid-template-columns: repeat(2, 1fr); gap: 0.75rem; }
    }
    @media (max-width: 420px) {
        .hero-titulo { font-size: 1.9rem; }
        .productos-grid { grid-template-columns: 1fr; }
    }
    
    .producto-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }
    
    .producto-imagen {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f3f4f6;
    }
    
    .producto-info {
        padding: 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .producto-nombre {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-size: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .producto-precio {
        font-size: 1.25rem;
        font-weight: 700;
        color: #059669;
        margin-bottom: 0.75rem;
    }
    
    .btn-añadir {
        background: #059669;
        color: white;
        border: none;
        padding: 0.625rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .btn-añadir:hover {
        background: #047857;
    }

    /* Categories Section */
    .categorias-section {
        background: #f9fafb;
        padding: 4rem 0;
        margin-top: 4rem;
    }
    
    .categorias-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    
    .categorias-titulo {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #1f2937;
        font-weight: 700;
        text-align: center;
        margin-bottom: 3rem;
    }
    
    .categorias-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 2rem;
    }
    
    .categoria-item {
        text-align: center;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s;
    }
    
    .categoria-item:hover {
        transform: translateY(-4px);
    }
    
    .categoria-icon {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2rem;
        color: #059669;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.2s;
    }
    
    .categoria-item:hover .categoria-icon {
        background: #059669;
        color: white;
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
    }
    
    .categoria-nombre {
        font-weight: 600;
        color: #374151;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-titulo {
            font-size: 2rem;
        }
        
        .hero-subtitulo {
            font-size: 1rem;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .productos-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }
        
        .producto-imagen {
            height: 150px;
        }
        
        .categorias-grid {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 1.5rem;
        }
        
        .categoria-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('contenido')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-titulo">Encuentra todo lo que necesitas</h1>
        <p class="hero-subtitulo">Desde productos frescos hasta artículos para el hogar, todo a un clic de distancia.</p>
        
        <form action="{{ route('tienda.index') }}" method="GET" class="hero-search">
            <input type="text" name="search" placeholder="Buscar productos..." value="{{ request('search') }}">
            <i class="fas fa-search"></i>
        </form>
    </div>
</section>

<!-- Productos Destacados -->
<section class="productos-section">
    <div class="section-header">
        <h2 class="section-titulo">Productos Destacados</h2>
        <a href="{{ route('tienda.index') }}" class="ver-todos">
            Ver todos los productos <i class="fas fa-arrow-right"></i>
        </a>
    </div>
    
    <div class="productos-grid">
        @php
            $productosDestacados = \App\Models\Producto::where('estado', 'publicado')
                ->where('activo', true)
                ->with('vendedor')
                ->inRandomOrder()
                ->limit(4)
                ->get();
        @endphp
        
        @forelse($productosDestacados as $producto)
        <div class="producto-card">
            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                 alt="{{ $producto->nombre }}" 
                 class="producto-imagen">
            <div class="producto-info">
                <h3 class="producto-nombre">{{ $producto->nombre }}</h3>
                <div class="producto-precio">S/ {{ number_format($producto->precio, 2) }}</div>
                <form action="{{ route('cart.add', $producto) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-añadir">
                        <i class="fas fa-shopping-cart"></i>
                        Añadir al Carrito
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #9ca3af;">
            <i class="fas fa-box-open" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>No hay productos disponibles en este momento</p>
        </div>
        @endforelse
    </div>
</section>

<!-- Categorías -->
<section class="categorias-section">
    <div class="categorias-container">
        <h2 class="categorias-titulo">Explorar Categorías</h2>
        
        <div class="categorias-grid">
            @php
                $categorias = \App\Models\Categoria::all();
                $iconos = [
                    'fas fa-wine-glass-alt',
                    'fas fa-shopping-basket',
                    'fas fa-utensils',
                    'fas fa-leaf',
                    'fas fa-paw',
                    'fas fa-home'
                ];
            @endphp
            
            @forelse($categorias as $index => $categoria)
            <a href="{{ route('tienda.index', ['categoria' => $categoria->id]) }}" class="categoria-item">
                <div class="categoria-icon">
                    <i class="{{ $iconos[$index % count($iconos)] }}"></i>
                </div>
                <div class="categoria-nombre">{{ $categoria->nombre }}</div>
            </a>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: #9ca3af;">
                <p>No hay categorías disponibles</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
