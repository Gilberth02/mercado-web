@extends('layouts.principal')
@section('titulo','Panel de Administrador - Moderación')

@section('contenido')
<div class="contenedor">
    <h1>Panel de Moderación de Productos</h1>
    <p>Aquí están los productos pendientes y publicados (puedes deshabilitar los publicados sin eliminarlos).</p>
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
                <th>Estado</th>
                <th>Activo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>
                        {{-- Recuerda que debes tener el 'storage link' creado --}}
                        <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-producto">
                    </td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->vendedor->nombre_negocio }}</td>
                    <td>S/ {{ $producto->precio }}</td>
                    <td><strong>{{ ucfirst($producto->estado) }}</strong></td>
                    <td>{{ $producto->activo ? 'Sí' : 'No' }}</td>
                    <td class="acciones">
                        {{-- Si está pendiente o hay una propuesta de edición: mostrar Aprobar o Rechazar --}}
                        @if($producto->estado === 'pendiente' || $producto->propuesta_edicion)
                            <form action="{{ route('admin.productos.aprobar', $producto) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-aprobar">Aprobar</button>
                            </form>
                            {{-- Formulario para RECHAZAR: solicitamos motivo si está pendiente o hay propuesta --}}
                            <form action="{{ route('admin.productos.rechazar', $producto) }}" method="POST" style="display:inline;margin-left:8px;" onsubmit="return adminRejectHandler(event, this, '{{ $producto->estado }}', '{{ $producto->propuesta_edicion ? 1 : 0 }}');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="motivo" value="" class="motivo-input">
                                <button type="submit" class="btn-rechazar">Rechazar</button>
                            </form>

                        @else
                            {{-- Si ya está publicado (u otro estado), mostrar acciones adicionales --}}

                            {{-- Toggle activo solo para productos publicados --}}
                            <form action="{{ route('admin.productos.toggle', $producto) }}" method="POST" style="display:inline">
                                @csrf
                                @method('PATCH')
                                @if($producto->activo)
                                    <button type="submit" class="btn-rechazar">Deshabilitar</button>
                                @else
                                    <button type="submit" class="btn-aprobar">Habilitar</button>
                                @endif
                            </form>

                            {{-- Eliminar (por si el admin desea borrarlo) --}}
                            <form action="{{ route('admin.productos.rechazar', $producto) }}" method="POST" style="display:inline;margin-left:8px;" onsubmit="return adminRejectHandler(event, this, '{{ $producto->estado }}', '{{ $producto->propuesta_edicion ? 1 : 0 }}');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="motivo" value="" class="motivo-input">
                                <button type="submit" class="btn-rechazar">Eliminar</button>
                            </form>

                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">
                        ¡No hay productos pendientes por revisar!
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection

@section('scripts')
<script>
    let pendingRejectForm = null;

    function adminRejectHandler(event, form, estado, hasProposal) {
        // Si está pendiente o existe una propuesta de edición, abrimos un modal para capturar el motivo
        if (estado === 'pendiente' || hasProposal == '1') {
            event.preventDefault();
            pendingRejectForm = form;
            // limpiar textarea
            document.getElementById('rechazo-motivo-text').value = '';
            // mostrar modal
            document.getElementById('rechazo-modal').style.display = 'flex';
            document.getElementById('rechazo-motivo-text').focus();
            return false;
        }

        // Para otros estados, confirmar eliminación como antes
        return confirm('¿Deseas eliminar este producto?');
    }

    function closeRechazoModal() {
        document.getElementById('rechazo-modal').style.display = 'none';
        pendingRejectForm = null;
    }

    document.addEventListener('DOMContentLoaded', function(){
        document.getElementById('rechazo-cancel-btn').addEventListener('click', function(e){
            e.preventDefault();
            closeRechazoModal();
        });

        document.getElementById('rechazo-submit-btn').addEventListener('click', function(e){
            e.preventDefault();
            const text = document.getElementById('rechazo-motivo-text').value || '';
            if (text.trim().length === 0) {
                alert('Debes indicar un motivo para el rechazo.');
                document.getElementById('rechazo-motivo-text').focus();
                return;
            }
            if (!pendingRejectForm) { closeRechazoModal(); return; }
            const input = pendingRejectForm.querySelector('.motivo-input');
            if (input) input.value = text;
            pendingRejectForm.submit();
            closeRechazoModal();
        });
    });
</script>
@endsection

<!-- Modal de rechazo -->
<div id="rechazo-modal" style="display:none;position:fixed;inset:0;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);z-index:2000">
    <div style="background:#fff;padding:18px;border-radius:8px;max-width:640px;width:92%;box-shadow:0 8px 30px rgba(0,0,0,0.2)">
        <h3 style="margin-top:0">Motivo del rechazo</h3>
        <p>Escribe un comentario breve para que el vendedor sepa por qué su producto fue rechazado.</p>
        <textarea id="rechazo-motivo-text" style="width:100%;min-height:120px;padding:10px;border:1px solid #ddd;border-radius:6px" placeholder="Motivo del rechazo..."></textarea>
        <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:12px">
            <button id="rechazo-cancel-btn" style="background:#6c757d;color:#fff;padding:8px 12px;border-radius:6px;border:none;">Cancelar</button>
            <button id="rechazo-submit-btn" style="background:#d9534f;color:#fff;padding:8px 12px;border-radius:6px;border:none;">Enviar y rechazar</button>
        </div>
    </div>
</div>