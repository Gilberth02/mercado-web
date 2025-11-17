@extends('layouts.principal')
@section('titulo','Panel de Administrador - Moderación')

@section('contenido')
<div class="contenedor">
    <h1>Panel de Moderación de Productos</h1>
    <p>Aquí están los productos pendientes de revisión.</p>
    <style>
    .nav-admin { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .nav-admin a { padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: 700; }
    .nav-admin a.activo { background-color: #1ca69a; color: white; }
    .nav-admin a.inactivo { background-color: #f0f0f0; color: #555; }
</style>

<nav class="nav-admin">
    <a href="{{ route('admin.productos.index') }}" class="activo">Moderar Productos</a>
    <a href="{{ route('admin.repartidores.index') }}" class="inactivo">Ver Repartidores</a>
    <a href="{{ route('admin.categorias.index') }}" class="inactivo">Gestionar Categorías</a> 
</nav>
</nav>

    <hr>

    @if(session('success'))
      <div class="alerta-exito" style="color: green; background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        {{ session('success') }}
      </div>
    @endif

    {{-- Estilos rápidos para la tabla --}}
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .img-producto { width: 100px; height: 100px; object-fit: cover; }
        .acciones form { display: inline-block; }
        .btn-aprobar { background: green; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px; }
        .btn-rechazar { background: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px; }
    </style>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Vendedor</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productosPendientes as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>
                        {{-- Recuerda que debes tener el 'storage link' creado --}}
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-producto">
                    </td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->vendedor->nombre_negocio }}</td>
                    <td>S/ {{ $producto->precio }}</td>
                    <td class="acciones">
                        
                        {{-- Botón de APROBAR --}}
                        <form action="{{ route('admin.productos.aprobar', $producto) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-aprobar">Aprobar</button>
                        </form>

                        {{-- Botón de RECHAZAR --}}
                        <form action="{{ route('admin.productos.rechazar', $producto) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-rechazar">Rechazar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        ¡No hay productos pendientes por revisar!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection