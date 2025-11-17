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
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-carrito">
                    </td>
                    <td>{{ $producto->nombre }}</td>
                    <td>S/ {{ number_format($producto->precio, 2) }}</td>
                    <td>
                        {{-- Aquí obtenemos la cantidad desde la sesión --}}
                        {{ $cart[$producto->id]['quantity'] }}
                    </td>
                    <td>
                        {{-- Calculamos el subtotal --}}
                        S/ {{ number_format($producto->precio * $cart[$producto->id]['quantity'], 2) }}
                    </td>
                    <td>
                        {{-- Botón de Eliminar --}}
                        <form action="{{ route('cart.remove', $producto) }}" method="POST">
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
            <a href="#" class="btn-pagar">Proceder al Pago</a>
        </div>
    @endif

</div>
@endsection