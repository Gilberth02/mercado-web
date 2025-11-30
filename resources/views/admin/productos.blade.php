@extends('layouts.principal')
@section('titulo','Panel de Administrador - Moderación')

@section('estilos')
<style>
    * { box-sizing: border-box; }
    
    .admin-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    
    .admin-header {
        margin-bottom: 2rem;
    }
    
    .admin-titulo {
        font-size: 2rem;
        color: #1f2937;
        margin-bottom: 0.5rem;
        font-family: 'Playfair Display', serif;
    }
    
    .admin-subtitulo {
        color: #6b7280;
        font-size: 0.95rem;
    }

    /* Nav Tabs */
    .nav-admin {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 0.25rem;
        flex-wrap: wrap;
    }
    
    .nav-admin a {
        padding: 0.75rem 1.5rem;
        border-radius: 8px 8px 0 0;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
        border: 2px solid transparent;
        border-bottom: none;
    }
    
    .nav-admin a.activo {
        background-color: #059669;
        color: white;
    }
    
    .nav-admin a.inactivo {
        background-color: #f9fafb;
        color: #6b7280;
    }
    
    .nav-admin a.inactivo:hover {
        background-color: #f3f4f6;
        color: #059669;
    }

    /* Alert */
    .alerta-exito {
        color: #059669;
        background: #d1fae5;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #059669;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
        gap: 1rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }
    
    .stat-icon.pendientes { background: #fef3c7; color: #f59e0b; }
    .stat-icon.publicados { background: #d1fae5; color: #059669; }
    .stat-icon.rechazados { background: #fee2e2; color: #dc2626; }
    .stat-icon.total { background: #dbeafe; color: #2563eb; }
    
    .stat-info {
        flex: 1;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        font-size: 0.95rem;
        color: #6b7280;
    }

    /* Section Card */
    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .section-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .section-titulo {
        font-size: 1.5rem;
        color: #1f2937;
        margin: 0;
        font-family: 'Playfair Display', serif;
    }

    /* Table Wrapper */
    .tabla-wrapper {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 600px;
    }
    
    .tabla-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .tabla-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .tabla-wrapper::-webkit-scrollbar-thumb {
        background: #059669;
        border-radius: 4px;
    }

    /* Modern Table */
    .tabla-moderna {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .tabla-moderna thead {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f9fafb;
    }
    
    .tabla-moderna th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 2px solid #e5e7eb;
        white-space: nowrap;
    }
    
    .tabla-moderna td {
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
        color: #4b5563;
    }
    
    .tabla-moderna tbody tr {
        transition: background-color 0.15s;
    }
    
    .tabla-moderna tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .producto-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
    }

    /* Badge */
    .badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    
    .badge.pendiente {
        background: #fef3c7;
        color: #92400e;
    }
    
    .badge.publicado {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge.rechazado {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .badge.activo {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge.inactivo {
        background: #f3f4f6;
        color: #6b7280;
    }

    /* Buttons */
    .btn-sm {
        padding: 0.5rem 0.875rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        text-decoration: none;
    }
    
    .btn-aprobar {
        background: #059669;
        color: white;
    }
    
    .btn-aprobar:hover {
        background: #047857;
    }
    
    .btn-rechazar {
        background: #dc2626;
        color: white;
    }
    
    .btn-rechazar:hover {
        background: #b91c1c;
    }
    
    .btn-toggle {
        background: #3b82f6;
        color: white;
    }
    
    .btn-toggle:hover {
        background: #2563eb;
    }
    
    .btn-eliminar {
        background: #ef4444;
        color: white;
    }
    
    .btn-eliminar:hover {
        background: #dc2626;
    }

    .acciones-grupo {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .acciones-grupo form {
        margin: 0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Modal */
    .modal {
        position: fixed;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0,0,0,0.5);
        z-index: 2000;
    }
    
    .modal-content {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        max-width: 640px;
        width: 92%;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .modal-titulo {
        font-size: 1.5rem;
        color: #1f2937;
        margin: 0;
        font-family: 'Playfair Display', serif;
    }
    
    .modal-close {
        background: transparent;
        border: none;
        font-size: 2rem;
        color: #9ca3af;
        cursor: pointer;
        line-height: 1;
        padding: 0;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-close:hover {
        color: #4b5563;
    }
    
    .modal textarea {
        width: 100%;
        min-height: 120px;
        padding: 0.875rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.95rem;
        resize: vertical;
    }
    
    .modal textarea:focus {
        outline: none;
        border-color: #059669;
    }
    
    .modal-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }
    
    .btn-cancelar {
        background: #6b7280;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-cancelar:hover {
        background: #4b5563;
    }
    
    .btn-confirmar {
        background: #dc2626;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-confirmar:hover {
        background: #b91c1c;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-container {
            padding: 0 1rem;
        }
        
        .admin-titulo {
            font-size: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .tabla-wrapper {
            max-height: 400px;
        }
        
        .producto-img {
            width: 60px;
            height: 60px;
        }
    }
</style>
@endsection

@section('contenido')

<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-titulo">Panel de Moderación de Productos</h1>
        <p class="admin-subtitulo">Gestiona los productos pendientes y publicados de la plataforma</p>
    </div>

    <nav class="nav-admin">
        <a href="{{ route('admin.productos.index') }}" class="activo">Moderar Productos</a>
        <a href="{{ route('admin.repartidores.index') }}" class="inactivo">Ver Repartidores</a>
        <a href="{{ route('admin.categorias.index') }}" class="inactivo">Gestionar Categorías</a> 
    </nav>

    @if(session('success'))
    <div class="alerta-exito">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Estadísticas --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon pendientes">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $productos->where('estado', 'pendiente')->count() }}</div>
                <div class="stat-label">Pendientes</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon publicados">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $productos->where('estado', 'publicado')->count() }}</div>
                <div class="stat-label">Publicados</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon rechazados">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $productos->where('estado', 'rechazado')->count() }}</div>
                <div class="stat-label">Rechazados</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $productos->count() }}</div>
                <div class="stat-label">Total Productos</div>
            </div>
        </div>
    </div>

    {{-- Tabla de productos --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Lista de Productos</h2>
        </div>

        @if($productos->count())
        <div class="tabla-wrapper">
            <table class="tabla-moderna">
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
                    @foreach ($productos as $producto)
                    <tr>
                        <td><strong>#{{ $producto->id }}</strong></td>
                        <td>
                            <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                 alt="{{ $producto->nombre }}" 
                                 class="producto-img">
                        </td>
                        <td><strong>{{ $producto->nombre }}</strong></td>
                        <td>{{ $producto->vendedor->nombre_negocio }}</td>
                        <td><strong>S/ {{ number_format($producto->precio, 2) }}</strong></td>
                        <td>
                            <span class="badge {{ strtolower($producto->estado) }}">
                                {{ ucfirst($producto->estado) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $producto->activo ? 'activo' : 'inactivo' }}">
                                {{ $producto->activo ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <div class="acciones-grupo" data-producto-id="{{ $producto->id }}" data-producto-row="true">
                                @if($producto->estado === 'pendiente' || $producto->propuesta_edicion)
                                    {{-- Aprobar --}}
                                    <form class="ajax-admin-form" action="{{ route('admin.productos.aprobar', $producto) }}" method="POST" data-action="aprobar">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-sm btn-aprobar">
                                            <i class="fas fa-check"></i> Aprobar
                                        </button>
                                    </form>
                                    
                                    {{-- Rechazar --}}
                                    <form class="ajax-admin-form" action="{{ route('admin.productos.rechazar', $producto) }}" 
                                          method="POST" 
                                          data-action="rechazar"
                                          data-estado="{{ $producto->estado }}"
                                          data-has-proposal="{{ $producto->propuesta_edicion ? 1 : 0 }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="motivo" value="" class="motivo-input">
                                        <button type="submit" class="btn-sm btn-rechazar">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </form>
                                @else
                                    {{-- Toggle activo --}}
                                    <form class="ajax-admin-form" action="{{ route('admin.productos.toggle', $producto) }}" method="POST" data-action="toggle">
                                        @csrf
                                        @method('PATCH')
                                        @if($producto->activo)
                                            <button type="submit" class="btn-sm btn-toggle">
                                                <i class="fas fa-ban"></i> Deshabilitar
                                            </button>
                                        @else
                                            <button type="submit" class="btn-sm btn-aprobar">
                                                <i class="fas fa-check"></i> Habilitar
                                            </button>
                                        @endif
                                    </form>
                                    
                                    {{-- Eliminar --}}
                                    <form class="ajax-admin-form" action="{{ route('admin.productos.rechazar', $producto) }}" 
                                          method="POST" 
                                          data-action="eliminar"
                                          data-estado="{{ $producto->estado }}"
                                          data-has-proposal="{{ $producto->propuesta_edicion ? 1 : 0 }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="motivo" value="" class="motivo-input">
                                        <button type="submit" class="btn-sm btn-eliminar">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
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
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No hay productos para revisar</p>
            <p>Los productos pendientes de moderación aparecerán aquí</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal de rechazo -->
<div id="rechazo-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-titulo">Motivo del rechazo</h3>
            <button class="modal-close" onclick="closeRechazoModal()">×</button>
        </div>
        <p style="color: #6b7280; margin-bottom: 1rem;">
            Escribe un comentario breve para que el vendedor sepa por qué su producto fue rechazado.
        </p>
        <textarea id="rechazo-motivo-text" placeholder="Motivo del rechazo..."></textarea>
        <div class="modal-buttons">
            <button id="rechazo-cancel-btn" class="btn-cancelar">Cancelar</button>
            <button id="rechazo-submit-btn" class="btn-confirmar">Enviar y rechazar</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let pendingRejectForm = null;

    function closeRechazoModal() {
        document.getElementById('rechazo-modal').style.display = 'none';
        pendingRejectForm = null;
    }

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

        setTimeout(() => alert.remove(), 4000);
    }

    document.addEventListener('DOMContentLoaded', function(){
        // Modal handlers
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
            if (!pendingRejectForm) { 
                closeRechazoModal(); 
                return; 
            }
            
            // Submit via AJAX
            const input = pendingRejectForm.querySelector('.motivo-input');
            if (input) input.value = text;
            submitFormAjax(pendingRejectForm);
            closeRechazoModal();
        });

        // AJAX form handler
        document.querySelectorAll('.ajax-admin-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const action = this.dataset.action;
                const estado = this.dataset.estado;
                const hasProposal = this.dataset.hasProposal;

                // Si es rechazar o eliminar y necesita motivo
                if ((action === 'rechazar' || action === 'eliminar') && 
                    (estado === 'pendiente' || hasProposal == '1')) {
                    pendingRejectForm = this;
                    document.getElementById('rechazo-motivo-text').value = '';
                    document.getElementById('rechazo-modal').style.display = 'flex';
                    document.getElementById('rechazo-motivo-text').focus();
                    return;
                }

                // Confirmar eliminación para productos ya aprobados
                if (action === 'eliminar' && !confirm('¿Deseas eliminar este producto?')) {
                    return;
                }

                submitFormAjax(this);
            });
        });

        function submitFormAjax(form) {
            const btn = form.querySelector('button[type="submit"]');
            const originalBtnText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const formData = new FormData(form);
            const actionUrl = form.action;

            fetch(actionUrl, {
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
                    showMessage(data.message || 'Operación exitosa');
                    
                    // Recargar la página después de 1 segundo para actualizar la lista
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showMessage(data.message || 'Error en la operación', 'error');
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
        }
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