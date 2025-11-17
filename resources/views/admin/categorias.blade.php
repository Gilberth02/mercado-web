@extends('layouts.principal')
@section('titulo','Panel de Administrador - Categorías')

@section('contenido')
<div class="contenedor">
    <h1>Panel de Administrador</h1>
    
    {{-- Menú de Navegación del Admin --}}
    <style>
        .nav-admin { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .nav-admin a { padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: 700; }
        .nav-admin a.activo { background-color: #1ca69a; color: white; }
        .nav-admin a.inactivo { background-color: #f0f0f0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
    
    <nav class="nav-admin">
        <a href="{{ route('admin.productos.index') }}" class="inactivo">Moderar Productos</a>
        <a href="{{ route('admin.repartidores.index') }}" class="inactivo">Ver Repartidores</a>
        <a href="{{ route('admin.categorias.index') }}" class="activo">Gestionar Categorías</a>
    </nav>
    
    <h2>Crear Nueva Categoría</h2>
    
    @if(session('success'))
      <div class="alerta-exito" style="color: green; background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        {{ session('success') }}
      </div>
    @endif

    {{-- Aquí está el formulario y el botón que pediste --}}
    <form action="{{ route('admin.categorias.store') }}" method="POST" style="display: flex; gap: 10px;">
        @csrf
        <label class="campo" style="flex-grow: 1;">
            <span class="etiqueta">Nombre de la nueva categoría:</span>
            <input class="entrada" type="text" name="nombre" value="{{ old('nombre') }}" required>
        </label>
        <button type"submit" class="boton" style="height: 45px; margin-top: 25px;">Crear Categoría</button>
    </form>
    @error('nombre')
      <span class="error-laravel" style="color: red; font-size: 13px;">{{ $message }}</span>
    @enderror

    <hr style="margin-top: 30px;">
    
    <h2>Categorías Existentes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categorias as $categoria)
                <tr>
                    <td>{{ $categoria->id }}</td>
                    <td>{{ $categoria->nombre }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center; padding: 20px;">
                        No hay categorías creadas.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection