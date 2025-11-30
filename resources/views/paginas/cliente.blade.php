@extends('layouts.principal')
@section('titulo','Panel Cliente')

@section('estilos')
<style>
    * { box-sizing: border-box; }
    
    .cliente-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    
    .cliente-header {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    
    .cliente-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #059669;
        flex-shrink: 0;
    }
    
    .cliente-info h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #1f2937;
        margin: 0 0 0.5rem 0;
    }
    
    .cliente-info p {
        color: #6b7280;
        margin: 0;
        font-size: 1rem;
    }
    
    .opciones-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
    }
    
    .opcion-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .opcion-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }
    
    .opcion-imagen {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .opcion-content {
        padding: 1.5rem;
    }
    
    .opcion-content h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #1f2937;
        margin: 0 0 0.75rem 0;
    }
    
    .opcion-content p {
        color: #6b7280;
        margin: 0 0 1.5rem 0;
        line-height: 1.6;
    }
    
    .btn-opcion {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        background: #059669;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.2s;
        text-align: center;
    }
    
    .btn-opcion:hover {
        background: #047857;
    }
    
    .btn-opcion.secondary {
        background: #3b82f6;
    }
    
    .btn-opcion.secondary:hover {
        background: #2563eb;
    }
    
    .badge-activo {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background: #d1fae5;
        color: #065f46;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .cliente-header {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }
        
        .cliente-info h1 {
            font-size: 1.5rem;
        }
        
        .opciones-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
</style>
@endsection

@section('contenido')
<div class="cliente-container">
    <div class="cliente-header">
        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="cliente-avatar" onerror="this.onerror=null; this.src='{{ asset('Vista/img/avatar.png') }}'">
        <div class="cliente-info">
            <h1>Panel Cliente</h1>
            <p>Hola, <strong>{{ Auth::user()->name }}</strong>. Desde aquí puedes transformar tu cuenta o gestionar tu información.</p>
        </div>
    </div>

    <div class="opciones-grid">
        <div class="opcion-card">
            <img src="{{ asset('Vista/img/vendedor.webp') }}" alt="Vendedor" class="opcion-imagen">
            <div class="opcion-content">
                <h3>
                    Vendedor
                    @if(Auth::user()->vendedor)
                        <span class="badge-activo"><i class="fas fa-check"></i> Activo</span>
                    @endif
                </h3>
                <p>Vende tus productos en la tienda y gestiona tus ventas desde tu panel.</p>
                @if(Auth::user()->vendedor)
                    <a href="{{ route('vendedor.panel') }}" class="btn-opcion secondary">
                        <i class="fas fa-chart-line"></i> Ir a mi panel
                    </a>
                @else
                    <button type="button" class="btn-opcion" id="abrirModalVendedor">
                        <i class="fas fa-plus-circle"></i> Convertirme en Vendedor
                    </button>
                @endif
            </div>
        </div>

        <div class="opcion-card">
            <img src="{{ asset('Vista/img/repartidor.webp') }}" alt="Delivery" class="opcion-imagen">
            <div class="opcion-content">
                <h3>
                    Delivery
                    @php($perfilRep = Auth::user()->repartidor)
                    @if($perfilRep && ($perfilRep->estado ?? null) === 'aprobado')
                        <span class="badge-activo"><i class="fas fa-check"></i> Activo</span>
                    @elseif($perfilRep && ($perfilRep->estado ?? null) === 'pendiente')
                        <span class="badge-activo" style="background:#fef3c7; color:#92400e;"><i class="fas fa-hourglass-half"></i> Pendiente</span>
                    @elseif($perfilRep && ($perfilRep->estado ?? null) === 'rechazado')
                        <span class="badge-activo" style="background:#fee2e2; color:#991b1b;"><i class="fas fa-times-circle"></i> Rechazado</span>
                    @endif
                </h3>
                <p>Recibe pedidos para repartir y gestiona tus entregas desde el panel de repartidor.</p>
                @php($estadoRep = $perfilRep->estado ?? null)
                @if($perfilRep && $estadoRep === 'aprobado')
                    <a href="{{ route('repartidor.panel') }}" class="btn-opcion secondary">
                        <i class="fas fa-shipping-fast"></i> Ir a mi panel
                    </a>
                @else
                    <button type="button" class="btn-opcion" id="abrirModalDelivery">
                        <i class="fas fa-plus-circle"></i> {{ $estadoRep === 'rechazado' ? 'Reenviar solicitud' : 'Convertirme en Delivery' }}
                    </button>
                @endif
            </div>
        </div>

        <div class="opcion-card">
            <img src="{{ asset('Vista/img/cliente.webp') }}" alt="Perfil" class="opcion-imagen">
            <div class="opcion-content">
                <h3>Mi Perfil</h3>
                <p>Actualiza tus datos de contacto, dirección y preferencias de la cuenta.</p>
                <a href="{{ route('profile.edit') }}" class="btn-opcion secondary">
                    <i class="fas fa-user-edit"></i> Editar mi perfil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const abrir = document.getElementById('abrirModalVendedor');
    const overlay = document.getElementById('modalVendedorOverlay');
    const cerrar = document.getElementById('cerrarModalVendedor');
    const cerrar2 = document.getElementById('cerrarModalVendedor2');
    if (abrir && overlay && cerrar) {
        abrir.addEventListener('click', () => {
            overlay.classList.add('visible');
            document.body.style.overflow = 'hidden';
        });
        cerrar.addEventListener('click', () => {
            overlay.classList.remove('visible');
            document.body.style.overflow = '';
        });
        if (cerrar2) {
            cerrar2.addEventListener('click', () => {
                overlay.classList.remove('visible');
                document.body.style.overflow = '';
            });
        }
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.remove('visible');
                document.body.style.overflow = '';
            }
        });
    }
    // Delivery modal open/close
    const abrirD = document.getElementById('abrirModalDelivery');
    const overlayD = document.getElementById('modalDeliveryOverlay');
    const cerrarD = document.getElementById('cerrarModalDelivery');
    const cerrar2D = document.getElementById('cerrarModalDelivery2');
    if (abrirD && overlayD && cerrarD) {
        abrirD.addEventListener('click', () => {
            overlayD.classList.add('visible');
            document.body.style.overflow = 'hidden';
        });
        cerrarD.addEventListener('click', () => {
            overlayD.classList.remove('visible');
            document.body.style.overflow = '';
        });
        if (cerrar2D) {
            cerrar2D.addEventListener('click', () => {
                overlayD.classList.remove('visible');
                document.body.style.overflow = '';
            });
        }
        overlayD.addEventListener('click', (e) => {
            if (e.target === overlayD) {
                overlayD.classList.remove('visible');
                document.body.style.overflow = '';
            }
        });
    }
    // Auto-open modal from query param
    const params = new URLSearchParams(window.location.search);
    const open = params.get('open');
    if (open === 'vendedor') {
        const overlay = document.getElementById('modalVendedorOverlay');
        if (overlay) {
            overlay.classList.add('visible');
            document.body.style.overflow = 'hidden';
        }
    }
    if (open === 'delivery') {
        const overlayD = document.getElementById('modalDeliveryOverlay');
        if (overlayD) {
            overlayD.classList.add('visible');
            document.body.style.overflow = 'hidden';
        }
    }
});
</script>
<style>
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}
.modal-overlay.visible { display: flex; }
.modal-card {
    width: 95%;
    max-width: 520px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    overflow: hidden;
}
.modal-header { display:flex; align-items:center; justify-content:space-between; padding: 16px 18px; border-bottom:1px solid #eee; }
.modal-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; color:#1f2937; }
.modal-body { padding: 18px; }
.modal-actions { padding: 16px 18px; border-top:1px solid #eee; display:flex; gap:10px; justify-content:flex-end; }
.modal-close { background: transparent; border:none; font-size: 1.25rem; color:#6b7280; cursor:pointer; }
.entrada-modal { width: 100%; padding: 10px 12px; border:1px solid #ddd; border-radius: 8px; background:#f9fafb; }
.entrada-modal:focus { outline:none; border-color:#059669; background:#fff; }
.btn-primario { background:#059669; color:#fff; border:none; padding:10px 14px; border-radius:8px; cursor:pointer; font-weight:600; }
.btn-primario:hover { background:#047857; }
.btn-secundario { background:#f3f4f6; color:#374151; border:none; padding:10px 14px; border-radius:8px; cursor:pointer; font-weight:600; }
</style>

<div class="modal-overlay" id="modalVendedorOverlay">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalVendedorTitulo">
        <div class="modal-header">
            <h3 class="modal-title" id="modalVendedorTitulo"><i class="fas fa-user-tie"></i> Registro de Vendedor</h3>
            <button class="modal-close" id="cerrarModalVendedor" aria-label="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="color:#4b5563; margin-bottom:12px;">Completa tu información para crear tu perfil de vendedor.</p>
            <form method="POST" action="{{ route('vendedor.registro.store') }}">
                @csrf
                <label style="display:block; margin-bottom:10px;">
                    <span style="display:block; font-size:0.9rem; color:#374151; margin-bottom:6px;">Nombre del negocio</span>
                    <input type="text" name="nombre_negocio" class="entrada-modal" placeholder="Ej. Tienda Las Flores" required>
                </label>
                <label style="display:block; margin-bottom:10px;">
                    <span style="display:block; font-size:0.9rem; color:#374151; margin-bottom:6px;">Teléfono de contacto</span>
                    <input type="text" name="telefono" class="entrada-modal" placeholder="Ej. 987654321" minlength="9" maxlength="9" pattern="^\d{9}$" title="Debe tener 9 dígitos" required>
                </label>
                <label style="display:block; margin-bottom:10px;">
                    <span style="display:block; font-size:0.9rem; color:#374151; margin-bottom:6px;">Descripción breve</span>
                    <input type="text" name="descripcion" class="entrada-modal" placeholder="¿Qué vendes?" required>
                </label>
                <div class="modal-actions">
                    <button type="button" class="btn-secundario" id="cerrarModalVendedor2">Cancelar</button>
                    <button type="submit" class="btn-primario"><i class="fas fa-check"></i> Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

{{-- Modal Delivery --}}
<div class="modal-overlay" id="modalDeliveryOverlay">
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalDeliveryTitulo">
        <div class="modal-header">
            <h3 class="modal-title" id="modalDeliveryTitulo"><i class="fas fa-shipping-fast"></i> Registro de Delivery</h3>
            <button class="modal-close" id="cerrarModalDelivery" aria-label="Cerrar"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="color:#4b5563; margin-bottom:12px;">Completa tu información para crear tu perfil de delivery.</p>
            <form method="POST" action="{{ route('repartidor.registro.store') }}">
                @csrf
                <label style="display:block; margin-bottom:10px;">
                    <span style="display:block; font-size:0.9rem; color:#374151; margin-bottom:6px;">Vehículo</span>
                    <input type="text" name="vehiculo" class="entrada-modal" placeholder="Ej. Moto, Bicicleta" required>
                </label>
                <label style="display:block; margin-bottom:10px;">
                    <span style="display:block; font-size:0.9rem; color:#374151; margin-bottom:6px;">Matrícula</span>
                    <input type="text" name="matricula" class="entrada-modal" placeholder="Ej. ABC-123" required>
                </label>
                <label style="display:block; margin-bottom:10px;">
                    <span style="display:block; font-size:0.9rem; color:#374151; margin-bottom:6px;">Teléfono</span>
                    <input type="text" name="telefono" class="entrada-modal" placeholder="Ej. 987654321" minlength="9" maxlength="9" pattern="^\d{9}$" title="Debe tener 9 dígitos" required>
                </label>
                <div class="modal-actions">
                    <button type="button" class="btn-secundario" id="cerrarModalDelivery2">Cancelar</button>
                    <button type="submit" class="btn-primario"><i class="fas fa-check"></i> Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>