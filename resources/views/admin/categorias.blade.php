@extends('layouts.principal')
@section('titulo','Panel de Administrador - Categorías')

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
        display: flex;
        align-items: center;
        gap: 0.5rem;
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
    
    .stat-icon.categorias { background: #dbeafe; color: #2563eb; }
    
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

    .section-body {
        padding: 1.5rem;
    }

    /* Form */
    .form-grupo {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
        flex-wrap: wrap;
    }
    
    .campo {
        flex: 1;
        min-width: 250px;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .etiqueta {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
    }
    
    .entrada {
        padding: 0.875rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }
    
    .entrada:focus {
        outline: none;
        border-color: #059669;
    }
    
    .error-laravel {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Button */
    .btn-crear {
        padding: 0.875rem 1.5rem;
        background: #059669;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    
    .btn-crear:hover {
        background: #047857;
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
        
        .form-grupo {
            flex-direction: column;
            align-items: stretch;
        }
        
        .campo {
            min-width: 100%;
        }
        
        .btn-crear {
            width: 100%;
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
        <h1 class="admin-titulo">Panel de Categorías</h1>
        <p class="admin-subtitulo">Crea y gestiona las categorías de productos de la plataforma</p>
    </div>
    
    <nav class="nav-admin">
        <a href="{{ route('admin.productos.index') }}" class="inactivo">Moderar Productos</a>
        <a href="{{ route('admin.repartidores.index') }}" class="inactivo">Ver Repartidores</a>
        <a href="{{ route('admin.categorias.index') }}" class="activo">Gestionar Categorías</a>
    </nav>

    @if(session('success'))
    <div class="alerta-exito">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Estadísticas --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon categorias">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $categorias->count() }}</div>
                <div class="stat-label">Total Categorías</div>
            </div>
        </div>
    </div>

    {{-- Formulario para crear categoría --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Crear Nueva Categoría</h2>
        </div>
        <div class="section-body">
            <form id="form-crear-categoria" action="{{ route('admin.categorias.store') }}" method="POST">
                @csrf
                <div class="form-grupo">
                    <label class="campo">
                        <span class="etiqueta">Nombre de la categoría</span>
                        <input class="entrada" type="text" name="nombre" id="nombre-categoria" value="{{ old('nombre') }}" placeholder="Ej: Electrónica, Ropa, Alimentos..." required>
                    </label>
                    <button type="submit" class="btn-crear">
                        <i class="fas fa-plus"></i> Crear Categoría
                    </button>
                </div>
                <span class="error-mensaje" style="display:none; color: #dc2626; font-size: 0.875rem; margin-top: 0.5rem;"></span>
            </form>
        </div>
    </div>

    {{-- Tabla de categorías existentes --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Categorías Existentes</h2>
        </div>

        <div id="tabla-container">
        @if($categorias->count())
        <div class="tabla-wrapper">
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Fecha de Creación</th>
                    </tr>
                </thead>
                <tbody id="categorias-tbody">
                    @foreach ($categorias as $categoria)
                    <tr data-categoria-id="{{ $categoria->id }}">
                        <td><strong>#{{ $categoria->id }}</strong></td>
                        <td><strong>{{ $categoria->nombre }}</strong></td>
                        <td>{{ $categoria->created_at ? $categoria->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-tags"></i>
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No hay categorías creadas</p>
            <p>Crea tu primera categoría usando el formulario de arriba</p>
        </div>
        @endif
        </div>
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
        const form = document.getElementById('form-crear-categoria');
        const errorMsg = document.querySelector('.error-mensaje');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = this.querySelector('button[type="submit"]');
            const originalBtnText = btn.innerHTML;
            const nombreInput = document.getElementById('nombre-categoria');
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            errorMsg.style.display = 'none';

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.categoria) {
                    showMessage(data.message || '¡Categoría creada exitosamente!');
                    
                    // Limpiar el formulario
                    nombreInput.value = '';
                    
                    // Agregar la nueva categoría a la tabla
                    const tbody = document.getElementById('categorias-tbody');
                    const tableContainer = document.getElementById('tabla-container');
                    
                    // Si no hay tabla, crear una
                    if (!tbody) {
                        const now = new Date();
                        const fecha = now.toLocaleDateString('es-PE') + ' ' + now.toLocaleTimeString('es-PE', {hour: '2-digit', minute: '2-digit'});
                        
                        tableContainer.innerHTML = `
                            <div class="tabla-wrapper">
                                <table class="tabla-moderna">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Fecha de Creación</th>
                                        </tr>
                                    </thead>
                                    <tbody id="categorias-tbody">
                                        <tr data-categoria-id="${data.categoria.id}" style="background: #d1fae5;">
                                            <td><strong>#${data.categoria.id}</strong></td>
                                            <td><strong>${data.categoria.nombre}</strong></td>
                                            <td>${fecha}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        `;
                    } else {
                        // Agregar al inicio de la tabla existente
                        const now = new Date();
                        const fecha = now.toLocaleDateString('es-PE') + ' ' + now.toLocaleTimeString('es-PE', {hour: '2-digit', minute: '2-digit'});
                        
                        const newRow = document.createElement('tr');
                        newRow.dataset.categoriaId = data.categoria.id;
                        newRow.style.background = '#d1fae5';
                        newRow.innerHTML = `
                            <td><strong>#${data.categoria.id}</strong></td>
                            <td><strong>${data.categoria.nombre}</strong></td>
                            <td>${fecha}</td>
                        `;
                        tbody.insertBefore(newRow, tbody.firstChild);
                        
                        // Remover highlight después de 2 segundos
                        setTimeout(() => {
                            newRow.style.transition = 'background 0.5s ease';
                            newRow.style.background = '';
                        }, 2000);
                    }
                    
                    btn.disabled = false;
                    btn.innerHTML = originalBtnText;
                } else {
                    const errorText = data.message || 'Error al crear la categoría';
                    errorMsg.textContent = errorText;
                    errorMsg.style.display = 'block';
                    showMessage(errorText, 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalBtnText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorText = 'Error al procesar la solicitud';
                errorMsg.textContent = errorText;
                errorMsg.style.display = 'block';
                showMessage(errorText, 'error');
                btn.disabled = false;
                btn.innerHTML = originalBtnText;
            });
        });
    });
</script>
@endsection