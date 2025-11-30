@extends('layouts.principal')
@section('titulo','Panel de Administrador - Repartidores')

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
    
    .stat-icon.total { background: #dbeafe; color: #2563eb; }
    .stat-icon.disponibles { background: #d1fae5; color: #059669; }
    .stat-icon.ocupados { background: #fef3c7; color: #f59e0b; }
    
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
    
    .badge.disponible {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge.no-disponible {
        background: #fee2e2;
        color: #991b1b;
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

    /* Botones modernos para acciones */
    .btn-primario, .btn-secundario {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: transform 0.12s ease, box-shadow 0.2s ease, background 0.2s ease;
        text-decoration: none;
    }
    .btn-primario {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        box-shadow: 0 6px 14px rgba(16,185,129,0.25);
    }
    .btn-primario:hover { transform: translateY(-1px); box-shadow: 0 10px 20px rgba(16,185,129,0.3); }
    .btn-primario:active { transform: translateY(0); box-shadow: 0 4px 10px rgba(16,185,129,0.25); }

    .btn-secundario {
        background: #f3f4f6;
        color: #374151;
        box-shadow: 0 4px 10px rgba(55,65,81,0.08);
        border: 1px solid #e5e7eb;
    }
    .btn-secundario:hover { background: #e5e7eb; transform: translateY(-1px); }
    .btn-secundario:active { transform: translateY(0); }

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
    }
</style>
@endsection

@section('contenido')
<div class="admin-container">
    <div class="admin-header">
        <h1 class="admin-titulo">Panel de Repartidores</h1>
        <p class="admin-subtitulo">Gestiona los repartidores registrados en la plataforma</p>
    </div>
    
    <nav class="nav-admin">
        <a href="{{ route('admin.productos.index') }}" class="inactivo">Moderar Productos</a>
        <a href="{{ route('admin.repartidores.index') }}" class="activo">Ver Repartidores</a>
        <a href="{{ route('admin.categorias.index') }}" class="inactivo">Gestionar Categorías</a> 
    </nav>

    {{-- Estadísticas --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ ($aprobados ?? collect())->count() }}</div>
                <div class="stat-label">Total Aprobados</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon disponibles">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ ($aprobados ?? collect())->filter(fn($u) => $u->repartidor->disponible ?? false)->count() }}</div>
                <div class="stat-label">Disponibles</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon ocupados">
                <i class="fas fa-pause-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ ($aprobados ?? collect())->filter(fn($u) => !($u->repartidor->disponible ?? false))->count() }}</div>
                <div class="stat-label">No Disponibles</div>
            </div>
        </div>
    </div>

    {{-- Tabla de repartidores --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Solicitudes Pendientes</h2>
        </div>
            @if(($pendientes ?? collect())->count())
            <div class="tabla-wrapper">
                <table class="tabla-moderna">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Vehículo</th>
                            <th>Matrícula</th>
                            <th style="text-align:right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendientes as $rep)
                        <tr data-repartidor-id="{{ $rep->user_id }}">
                            <td><strong>#{{ $rep->user_id }}</strong></td>
                            <td><strong>{{ $rep->user->name }}</strong></td>
                            <td>{{ $rep->user->email }}</td>
                            <td>{{ $rep->user->telefono ?? 'N/A' }}</td>
                            <td>{{ $rep->vehiculo }}</td>
                            <td>{{ $rep->matricula }}</td>
                            <td style="text-align:right;">
                                <form class="ajax-repartidor-form" method="POST" action="{{ route('admin.repartidores.aprobar', $rep->user_id) }}" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-primario">Aprobar</button>
                                </form>
                                <form class="ajax-repartidor-form" method="POST" action="{{ route('admin.repartidores.rechazar', $rep->user_id) }}" style="display:inline-block; margin-left:8px;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-secundario">Rechazar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No hay solicitudes pendientes</p>
                <p>Las nuevas solicitudes aparecerán aquí para aprobación</p>
            </div>
            @endif
    </div>

        <div class="section-card">
            <div class="section-header">
                <h2 class="section-titulo">Repartidores Aprobados</h2>
            </div>
            @if(($aprobados ?? collect())->count())
            <div class="tabla-wrapper">
                <table class="tabla-moderna">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Vehículo</th>
                            <th>Matrícula</th>
                            <th>Disponible</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aprobados as $usuario)
                        <tr>
                            <td><strong>#{{ $usuario->id }}</strong></td>
                            <td><strong>{{ $usuario->name }}</strong></td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->telefono ?? 'N/A' }}</td>
                            <td>{{ $usuario->repartidor->vehiculo ?? 'N/A' }}</td>
                            <td>{{ $usuario->repartidor->matricula ?? 'N/A' }}</td>
                            <td>
                                @if($usuario->repartidor && $usuario->repartidor->disponible)
                                    <span class="badge disponible">
                                        <i class="fas fa-circle" style="font-size: 0.5rem;"></i> Disponible
                                    </span>
                                @else
                                    <span class="badge no-disponible">
                                        <i class="fas fa-circle" style="font-size: 0.5rem;"></i> No Disponible
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-truck"></i>
                <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No hay repartidores aprobados</p>
                <p>Aprueba solicitudes para verlos aquí</p>
            </div>
            @endif
        </div>
</div>
@endsection

@section('scripts')
<script>
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
        document.querySelectorAll('.ajax-repartidor-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = this.querySelector('button[type="submit"]');
                const originalBtnText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                const formData = new FormData(this);
                const actionUrl = this.action;

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
                        
                        // Remover la fila después de aprobar/rechazar
                        const row = this.closest('tr');
                        if (row) {
                            row.style.transition = 'opacity 0.3s ease';
                            row.style.opacity = '0';
                            setTimeout(() => {
                                row.remove();
                                
                                // Si no quedan más pendientes, mostrar mensaje vacío
                                const tbody = document.querySelector('.tabla-moderna tbody');
                                if (tbody && tbody.children.length === 0) {
                                    setTimeout(() => window.location.reload(), 500);
                                }
                            }, 300);
                        }
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
            });
        });
    });
</script>
@endsection