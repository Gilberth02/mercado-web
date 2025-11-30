@extends('layouts.principal')
@section('titulo','Panel de Vendedor')

@section('estilos')
<style>
    .vendedor-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .vendedor-header {
        margin-bottom: 1.5rem;
    }
    .vendedor-titulo {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    .bienvenida {
        background: #d1fae5;
        color: #065f46;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        margin-bottom: 2rem;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stat-info {
        flex: 1;
    }
    .stat-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-icon.productos {
        background: #dbeafe;
        color: #1e40af;
    }
    .stat-icon.activos {
        background: #d1fae5;
        color: #065f46;
    }
    .stat-icon.pendientes {
        background: #fef3c7;
        color: #92400e;
    }
    .stat-icon.ventas {
        background: #dcfce7;
        color: #166534;
    }
    .section-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }
    .tabla-wrapper {
        max-height: 600px;
        overflow-y: auto;
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .tabla-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    .tabla-wrapper::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 4px;
    }
    .tabla-wrapper::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    .tabla-wrapper::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .section-titulo {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
    }
    .btn-primary {
        background: #059669;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary:hover {
        background: #047857;
    }
    .tabla-moderna {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        overflow: hidden;
    }
    .tabla-moderna thead {
        background: #f9fafb;
    }
    .tabla-moderna th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e5e7eb;
    }
    .tabla-moderna td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        font-size: 0.875rem;
        color: #4b5563;
    }
    .tabla-moderna tbody tr:hover {
        background: #f9fafb;
    }
    .producto-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }
    .producto-nombre {
        font-weight: 600;
        color: #1f2937;
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge.publicado {
        background: #d1fae5;
        color: #065f46;
    }
    .badge.pendiente {
        background: #fef3c7;
        color: #92400e;
    }
    .badge.rechazado {
        background: #fee2e2;
        color: #991b1b;
    }
    .acciones-grupo {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .btn-sm {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        transition: opacity 0.2s;
    }
    .btn-sm:hover {
        opacity: 0.8;
    }
    .btn-editar {
        background: #fbbf24;
        color: white;
    }
    .btn-deshabilitar {
        background: #3b82f6;
        color: white;
    }
    .btn-eliminar {
        background: #ef4444;
        color: white;
    }
    .modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal.active {
        display: flex;
    }
    .modal-content {
        background: white;
        padding: 2rem; /* Original padding */
        border-radius: 12px;
        max-width: 900px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .modal-content {
            max-width: 95%;
            padding: 1rem;
            border-radius: 10px;
        }
        .modal-titulo {
            font-size: 1.25rem;
        }
        .form-input, .form-select, .form-textarea {
            padding: 0.65rem;
            font-size: 0.85rem;
        }
        .preview-container {
            flex-direction: column;
        }
        .preview-img {
            width: 100%;
            height: 180px;
        }
        .modal-footer {
            flex-direction: column;
            align-items: stretch;
        }
        .btn-secondary, .btn-primary {
            width: 100%;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .tabla-moderna {
            font-size: 0.75rem;
        }
    }
    
    /* Extra small devices */
    @media (max-width: 420px) {
        .modal-content {
            max-width: 98%;
            padding: 0.75rem;
        }
        .modal-titulo {
            font-size: 1.15rem;
        }
        .btn-secondary, .btn-primary {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }
    }
    .modal-header {
        margin-bottom: 1.5rem;
    }
    .modal-titulo {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group.full {
        grid-column: 1 / -1;
    }
    .form-label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    .form-input, .form-select, .form-textarea {
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        transition: border-color 0.2s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
    }
    .form-textarea {
        min-height: 100px;
        resize: vertical;
    }
    .preview-container {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    .preview-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }
    .modal-footer {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
    .btn-secondary {
        background: #6b7280;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-secondary:hover {
        background: #4b5563;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
    .empty-state i {
        font-size: 3rem;
        color: #d1d5db;
        margin-bottom: 1rem;
    }
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .tabla-moderna {
            font-size: 0.75rem;
        }
        .tabla-moderna th,
        .tabla-moderna td {
            padding: 0.5rem;
        }
    }
</style>
@endsection

@section('contenido')
<div class="vendedor-container">
    <div class="vendedor-header">
        <h1 class="vendedor-titulo">Panel de Vendedor</h1>
    </div>

    @if(session('success'))
    <div class="bienvenida">
        ¡Felicidades, {{ Auth::user()->name }}!
    </div>
    @else
    <div class="bienvenida">
        ¡Felicidades, {{ Auth::user()->name }}!
    </div>
    @endif

    {{-- Estadísticas --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Total Productos</div>
                <div class="stat-value">{{ $productos->count() }}</div>
            </div>
            <div class="stat-icon productos">
                <i class="fas fa-box"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Productos Activos</div>
                <div class="stat-value">{{ $productos->where('activo', true)->count() }}</div>
            </div>
            <div class="stat-icon activos">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Pedidos Pendientes</div>
                <div class="stat-value">{{ $pedidos->whereIn('estado', ['pendiente', 'en_camino'])->count() }}</div>
            </div>
            <div class="stat-icon pendientes">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <div class="stat-label">Ventas Totales</div>
                <div class="stat-value">S/{{ number_format($pedidos->where('estado', 'entregado')->sum('total'), 2) }}</div>
            </div>
            <div class="stat-icon ventas">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    {{-- Mis Productos --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Mis Productos</h2>
            <button id="abrir-nuevo-producto" class="btn-primary">
                <i class="fas fa-plus"></i> Añadir Nuevo Producto
            </button>
        </div>

        @if($productos && $productos->count() > 0)
        <div class="tabla-wrapper">
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productos as $p)
                    <tr id="producto-row-{{ $p->id }}">
                        <td>
                            @if($p->imagen)
                                <img src="{{ asset('storage/' . $p->imagen) }}" alt="{{ $p->nombre }}" class="producto-img">
                            @else
                                <div class="producto-img" style="display:flex;align-items:center;justify-content:center;background:#f3f4f6;color:#9ca3af;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="producto-nombre">{{ $p->nombre }}</div>
                            <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.25rem;">
                                Stock: {{ $p->stock }}
                            </div>
                        </td>
                        <td>S/ {{ number_format($p->precio, 2) }}</td>
                        <td>
                            <span class="badge {{ strtolower($p->estado) }}">
                                {{ ucfirst($p->estado) }}
                            </span>
                        </td>
                        <td>
                            <div class="acciones-grupo">
                                <a href="{{ route('vendedor.productos.edit', $p) }}" class="btn-sm btn-editar">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                
                                <form class="ajax-toggle" action="{{ route('vendedor.productos.toggle', $p) }}" method="POST" style="display:inline-block;margin:0;" data-product-id="{{ $p->id }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-sm btn-deshabilitar toggle-btn">
                                        {{ $p->activo ? 'Deshabilitar' : 'Habilitar' }}
                                    </button>
                                </form>

                                <form action="{{ route('vendedor.productos.destroy', $p) }}" method="POST" style="display:inline-block;margin:0;" onsubmit="return confirm('¿Eliminar este producto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-sm btn-eliminar">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>

                                @if($p->rechazo_motivo)
                                <button class="btn-motivo btn-sm" data-motivo="{{ e($p->rechazo_motivo) }}" style="background:#6b7280;color:white;">
                                    Motivo
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No has subido productos aún</p>
            <p>Comienza añadiendo tu primer producto</p>
        </div>
        @endif
    </div>

    {{-- Pedidos Relacionados --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Pedidos Relacionados</h2>
        </div>

        @if(isset($pedidos) && $pedidos->count() > 0)
        <div class="tabla-wrapper">
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    @php
                        $misDetalles = $pedido->detalles->filter(function($d){
                            return optional($d->producto)->vendedor_id === optional(Auth::user()->vendedor)->user_id;
                        });
                    @endphp
                    <tr>
                        <td><strong>#{{ sprintf('%06d', $pedido->id) }}</strong></td>
                        <td>{{ optional($pedido->cliente)->name ?? 'Invitado' }}</td>
                        <td>
                            <div style="font-size: 0.75rem;">
                                @foreach($misDetalles as $det)
                                    <div>{{ optional($det->producto)->nombre }} x {{ $det->cantidad }}</div>
                                @endforeach
                            </div>
                        </td>
                        <td><strong>S/ {{ number_format($pedido->total, 2) }}</strong></td>
                        <td>
                            @if($pedido->estado == 'entregado')
                                <span class="badge publicado">Entregado</span>
                            @elseif($pedido->estado == 'en_camino')
                                <span class="badge pendiente">En camino</span>
                            @else
                                <span class="badge pendiente">{{ ucfirst($pedido->estado) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($pedido->asignacion && $pedido->asignacion->repartidor)
                                @php
                                    $repUser = optional($pedido->asignacion->repartidor->user);
                                    $repPhone = $repUser->telefono ?? null;
                                    $telHref = $repPhone ? 'tel:+51' . preg_replace('/\s+/', '', $repPhone) : null;
                                @endphp
                                @if($repPhone)
                                    <a href="{{ $telHref }}" class="btn-sm" style="background:#059669;color:white;">
                                        <i class="fas fa-phone"></i> Contactar repartidor
                                    </a>
                                @else
                                    <span style="font-size: 0.75rem; color: #9ca3af;">Sin teléfono</span>
                                @endif
                            @else
                                <span style="font-size: 0.75rem; color: #9ca3af;">No asignado</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No hay pedidos relacionados</p>
            <p>Los pedidos que incluyan tus productos aparecerán aquí</p>
        </div>
        @endif
    </div>
</div>

    <!-- Modal Nuevo Producto -->
    <div id="modal-nuevo-producto" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-titulo">Añadir Nuevo Producto</h2>
                <p style="color: #6b7280; font-size: 0.875rem;">Este producto pasará a revisión antes de ser publicado</p>
            </div>

            <form action="{{ route('producto.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Nombre del Producto</label>
                        <input class="form-input" type="text" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <span style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" name="categoria_id" required>
                            <option value="">Selecciona una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('categoria_id')
                            <span style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Precio (S/.)</label>
                        <input class="form-input" type="number" step="0.01" name="precio" value="{{ old('precio') }}" required>
                        @error('precio')
                            <span style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stock (cantidad)</label>
                        <input class="form-input" type="number" name="stock" value="{{ old('stock') }}" required>
                        @error('stock')
                            <span style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Descripción</label>
                        <textarea class="form-textarea" name="descripcion">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <span style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Imagen del Producto</label>
                        <div class="preview-container">
                            <div style="flex: 1;">
                                <input class="form-input" id="imagen-input" type="file" name="imagen" accept="image/*" required>
                            </div>
                            <img id="preview" class="preview-img" src="{{ asset('Vista/img/placeholder.png') }}" alt="Preview">
                        </div>
                        @error('imagen')
                            <span style="color:#ef4444;font-size:0.75rem;margin-top:0.25rem;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="cerrar-modal-nuevo" class="btn-secondary">Cancelar</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-upload"></i> Subir Producto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview de imagen
    (function(){
        const input = document.getElementById('imagen-input');
        const preview = document.getElementById('preview');
        if(input && preview){
            input.addEventListener('change', function(e){
                const file = this.files && this.files[0];
                if(!file) {
                    preview.src = '{{ asset("Vista/img/placeholder.png") }}';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(ev){
                    preview.src = ev.target.result;
                };
                reader.readAsDataURL(file);
            });
        }
    })();

    // Control del modal
    (function(){
        const abrir = document.getElementById('abrir-nuevo-producto');
        const modal = document.getElementById('modal-nuevo-producto');
        const cerrar = document.getElementById('cerrar-modal-nuevo');
        
        if(abrir && modal){
            abrir.addEventListener('click', function(){
                modal.classList.add('active');
            });
        }
        
        if(cerrar && modal){
            cerrar.addEventListener('click', function(){
                modal.classList.remove('active');
            });
        }
        
        // Cerrar al hacer clic fuera
        if(modal){
            modal.addEventListener('click', function(e){
                if(e.target === modal){
                    modal.classList.remove('active');
                }
            });
        }
        
        // Abrir modal si hay errores de validación
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        if(hasErrors && modal){
            modal.classList.add('active');
        }
    })();

    // Toggle de productos con AJAX
    (function(){
        const token = '{{ csrf_token() }}';

        document.querySelectorAll('.ajax-toggle').forEach(function(form){
            form.addEventListener('submit', function(e){
                e.preventDefault();
                if(!confirm('¿Cambiar el estado de este producto?')) return;

                const action = form.action;
                const productId = form.getAttribute('data-product-id');
                const btn = form.querySelector('.toggle-btn');

                fetch(action, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                }).then(res => res.json())
                  .then(data => {
                    if(data && data.success){
                        if(btn) btn.textContent = data.activo ? 'Deshabilitar' : 'Habilitar';
                    } else {
                        alert('No se pudo actualizar el estado.');
                    }
                  }).catch(err => {
                    console.error(err);
                    alert('Error en la solicitud.');
                  });
            });
        });
    })();

    // Modal de motivo de rechazo
    (function(){
        document.addEventListener('click', function(e){
            const btn = e.target.closest('.btn-motivo');
            if(!btn) return;
            
            const motivo = btn.getAttribute('data-motivo') || '';
            const modal = document.createElement('div');
            modal.className = 'modal active';
            modal.innerHTML = `
                <div class="modal-content" style="max-width:600px;">
                    <div class="modal-header">
                        <h3 class="modal-titulo">Motivo del rechazo</h3>
                    </div>
                    <p style="white-space:pre-wrap;color:#4b5563;">${motivo}</p>
                    <div class="modal-footer">
                        <button id="cerrar-motivo-btn" class="btn-secondary">Cerrar</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            
            document.getElementById('cerrar-motivo-btn').addEventListener('click', function(){
                document.body.removeChild(modal);
            });
            
            modal.addEventListener('click', function(e){
                if(e.target === modal){
                    document.body.removeChild(modal);
                }
            });
        });
    })();
</script>
@endsection