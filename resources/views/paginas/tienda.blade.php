@extends('layouts.principal')
@section('titulo','Tienda - Mercado Web')

@section('contenido')
<div class="contenedor">
    <h1>Tienda</h1>
    <p>Explora todos nuestros productos locales.</p>

    {{-- Estilos rápidos para las tarjetas --}}
    <style>
        .grid-tienda { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .producto-card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .producto-card img { width: 100%; height: 200px; object-fit: cover; }
        .producto-info { padding: 15px; }
        .producto-info h3 { font-size: 18px; }
        .producto-info .precio { font-size: 16px; font-weight: bold; color: #1ca69a; }
        .producto-info .vendedor { font-size: 13px; color: #777; }
    </style>

    <div class="grid-tienda">
        @forelse ($productos as $producto)
            <div class="producto-card">
                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                @else
                    <div style="width:100%;height:200px;display:flex;align-items:center;justify-content:center;background:#f5f5f5;color:#999;">Imagen no disponible</div>
                @endif
                <div class="producto-info">
                    <h3>{{ $producto->nombre }}</h3>
                    <span class="precio">S/ {{ $producto->precio }}</span>
                    <p class="vendedor">Vendido por: {{ optional($producto->vendedor)->nombre_negocio ?? 'Desconocido' }}</p>
                    {{-- carrito --}}
                    <form action="{{ route('cart.add', $producto) }}" method="POST" style="margin-top: 10px;">
                        @csrf
                        <button type="submit" class="boton">Añadir al Carrito</button>
                    </form>
                </div>
            </div>
        @empty
            <p>No hay productos disponibles en este momento.</p>
        @endforelse
    </div>

</div>
@endsection