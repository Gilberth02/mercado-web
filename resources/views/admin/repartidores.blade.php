@extends('layouts.principal')
@section('titulo','Panel de Administrador - Repartidores')

@section('contenido')
<div class="contenedor">
    <h1>Panel de Administrador</h1>
    
    {{-- [NUEVO] Menú de Navegación del Admin --}}
    <style>
        .nav-admin { display: flex; gap: 10px; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .nav-admin a { padding: 8px 15px; border-radius: 5px; text-decoration: none; font-weight: 700; }
        .nav-admin a.activo { background-color: #1ca69a; color: white; }
        .nav-admin a.inactivo { background-color: #f0f0f0; color: #555; }
    </style>
    
    <nav class="nav-admin">
        <a href="{{ route('admin.productos.index') }}" class="inactivo">Moderar Productos</a>
        <a href="{{ route('admin.repartidores.index') }}" class="activo">Ver Repartidores</a>
        <a href="{{ route('admin.categorias.index') }}" class="inactivo">Gestionar Categorías</a> 
</nav>
    </nav>
    
    <h2>Lista de Repartidores</h2>
    
    {{-- (Los estilos de la tabla son los mismos que en productos.blade.php) --}}
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>

    <table>
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Vehículo</th>
                <th>Matrícula</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($repartidores as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    {{-- Accedemos a los datos del perfil 'repartidor' --}}
                    <td>{{ $usuario->repartidor->vehiculo ?? 'N/A' }}</td>
                    <td>{{ $usuario->repartidor->matricula ?? 'N/A' }}</td>
                    <td>
                        @if($usuario->repartidor->disponible)
                            <span style="color: green;">Disponible</span>
                        @else
                            <span style="color: red;">No Disponible</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">
                        No hay repartidores registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection