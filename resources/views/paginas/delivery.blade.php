@extends('layouts.principal')
@section('titulo','Panel de Repartidor')

@section('estilos')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
<style>
    .delivery-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .delivery-header {
        margin-bottom: 1.5rem;
    }
    .delivery-titulo {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }
    .bienvenida {
        background: #dbeafe;
        color: #1e3a8a;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .estado-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    .estado-badge.disponible {
        background: #d1fae5;
        color: #065f46;
    }
    .estado-badge.no-disponible {
        background: #fee2e2;
        color: #991b1b;
    }
    .btn-toggle {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-toggle:hover {
        background: #2563eb;
    }
    .section-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
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
    .tabla-moderna {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .tabla-moderna thead {
        background: #f9fafb;
        position: sticky;
        top: 0;
        z-index: 10;
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
    .badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge.asignado {
        background: #dbeafe;
        color: #1e40af;
    }
    .badge.por_recoger {
        background: #fef3c7;
        color: #92400e;
    }
    .badge.en_camino, .badge.en_ruta {
        background: #e0e7ff;
        color: #3730a3;
    }
    .badge.entregado {
        background: #d1fae5;
        color: #065f46;
    }
    .btn-sm {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        transition: opacity 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .btn-sm:hover {
        opacity: 0.8;
    }
    .btn-reclamar {
        background: #059669;
        color: white;
    }
    .btn-ver {
        background: #6b7280;
        color: white;
    }
    .btn-accion {
        background: #3b82f6;
        color: white;
    }
    .productos-lista {
        font-size: 0.75rem;
        margin: 0;
        padding-left: 1rem;
    }
    .tienda-grupo {
        margin-bottom: 0.5rem;
    }
    .tienda-nombre {
        font-weight: 600;
        color: #1f2937;
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
    .modal {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }
    .modal.active {
        display: flex;
    }
    .modal-content {
        background: white;
        width: 90%;
        max-width: 900px;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .modal-titulo {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937;
    }
    .modal-close {
        background: #6b7280;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .modal-close:hover {
        background: #4b5563;
    }
    #map-modal {
        height: 420px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(5,150,105,0.2);
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.95;
    }
    .review-item {
        background: #f9fafb;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #10b981;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    .review-user {
        font-weight: 600;
        color: #1f2937;
    }
    .review-date {
        font-size: 0.875rem;
        color: #6b7280;
    }
    .review-stars {
        color: #fbbf24;
        margin-bottom: 0.5rem;
    }
    .review-comment {
        color: #4b5563;
        line-height: 1.6;
    }
    .empty-reviews {
        text-align: center;
        padding: 3rem;
        color: #9ca3af;
    }
    .empty-reviews i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    @media (max-width: 768px) {
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
@php use Illuminate\Support\Str; @endphp
<div class="delivery-container">
    <div class="delivery-header">
        <h1 class="delivery-titulo">Panel de Repartidor</h1>
    </div>

    @if(Auth::user() && Auth::user()->repartidor)
    <div class="bienvenida">
        <div>
            <div style="margin-bottom: 0.5rem;">Hola, <strong>{{ Auth::user()->name }}</strong></div>
            <span class="estado-badge {{ Auth::user()->repartidor->disponible ? 'disponible' : 'no-disponible' }}" id="repartidor-disponible">
                <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                {{ Auth::user()->repartidor->disponible ? 'Disponible' : 'No disponible' }}
            </span>
        </div>
        <form class="ajax-action" action="{{ route('repartidor.toggle_disponible') }}" method="POST">
            @csrf
            <button class="btn-toggle">
                <i class="fas fa-sync-alt"></i>
                {{ Auth::user()->repartidor->disponible ? 'Marcar no disponible' : 'Marcar disponible' }}
            </button>
        </form>
    </div>
    @else
    <div class="bienvenida" style="background: #fef3c7; color: #92400e;">
        <div>No tienes perfil de repartidor</div>
        <a href="{{ route('cliente.redirect', ['open' => 'delivery']) }}" class="btn-toggle" style="text-decoration: none;">Registrarme</a>
    </div>
    @endif

    @if(Auth::user() && Auth::user()->repartidor)
    {{-- Rating Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">
                <i class="fas fa-star"></i>
                {{ $promedioCalificacion ?? 0 }}
            </div>
            <div class="stat-label">Calificación Promedio</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <div class="stat-value">
                <i class="fas fa-comment-alt"></i>
                {{ ($resenas ?? collect())->count() }}
            </div>
            <div class="stat-label">Reseñas Recibidas</div>
        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Mis Reseñas</h2>
        </div>
        @if(($resenas ?? collect())->count())
            @foreach($resenas as $resena)
            <div class="review-item">
                <div class="review-header">
                    <span class="review-user">
                        <i class="fas fa-user-circle"></i> {{ $resena->user->name }}
                    </span>
                    <span class="review-date">{{ $resena->created_at->diffForHumans() }}</span>
                </div>
                <div class="review-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star{{ $i <= $resena->puntuacion ? '' : '-o' }}"></i>
                    @endfor
                    <span style="color: #6b7280; font-size: 0.875rem; margin-left: 0.5rem;">
                        ({{ $resena->puntuacion }}/5)
                    </span>
                </div>
                @if($resena->comentario)
                <div class="review-comment">{{ $resena->comentario }}</div>
                @endif
                @if($resena->pedido)
                <div style="margin-top: 0.5rem; font-size: 0.875rem; color: #6b7280;">
                    <i class="fas fa-box"></i> Pedido #{{ $resena->pedido->id }}
                </div>
                @endif
            </div>
            @endforeach
        @else
        <div class="empty-reviews">
            <i class="fas fa-comment-slash"></i>
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">Aún no tienes reseñas</p>
            <p>Las reseñas de tus entregas aparecerán aquí</p>
        </div>
        @endif
    </div>
    @endif

    {{-- Pedidos disponibles --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Pedidos Disponibles</h2>
        </div>

        @if(isset($pendientes) && $pendientes->count())
        <div class="tabla-wrapper">
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Tienda / Productos</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendientes as $pedido)
                    <tr data-pedido-id="{{ $pedido->id }}" data-lat="{{ $pedido->lat ?? '' }}" data-lng="{{ $pedido->lng ?? '' }}" data-direc="{{ e($pedido->direccion) }}">
                        <td><strong>{{ optional($pedido->cliente)->name ?? 'Invitado' }}</strong></td>
                        <td>{{ $pedido->telefono }}</td>
                        <td>{{ Str::limit($pedido->direccion, 60) }}</td>
                        <td>
                            @php
                                $groups = [];
                                foreach($pedido->detalles as $det) {
                                    $prod = $det->producto;
                                    $v = optional($prod)->vendedor;
                                    $store = optional($v)->nombre_negocio ?? optional(optional($v)->user)->name ?? 'Tienda desconocida';
                                    $groups[$store][] = $det;
                                }
                            @endphp
                            @foreach($groups as $storeName => $items)
                            <div class="tienda-grupo">
                                <div class="tienda-nombre">{{ $storeName }}</div>
                                <ul class="productos-lista">
                                    @foreach($items as $it)
                                    <li>{{ optional($it->producto)->nombre ?? 'Producto eliminado' }} × {{ $it->cantidad }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </td>
                        <td><strong>S/ {{ number_format($pedido->total, 2) }}</strong></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <form class="ajax-action" action="{{ route('repartidor.asignar', $pedido->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn-sm btn-reclamar">
                                        <i class="fas fa-check"></i> Reclamar
                                    </button>
                                </form>
                                <button class="btn-sm btn-ver ver-mapa" 
                                        data-lat="{{ $pedido->lat ?? '' }}" 
                                        data-lng="{{ $pedido->lng ?? '' }}" 
                                        data-direc="{{ e($pedido->direccion) }}">
                                    <i class="fas fa-map-marker-alt"></i> Ver
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No hay pedidos disponibles</p>
            <p>Los nuevos pedidos aparecerán aquí cuando estén disponibles</p>
        </div>
        @endif
    </div>

    {{-- Mis asignaciones --}}
    <div class="section-card">
        <div class="section-header">
            <h2 class="section-titulo">Mis Asignaciones</h2>
        </div>

        @if(isset($asignados) && $asignados->count())
        <div class="tabla-wrapper">
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>Asignación Nº</th>
                        <th>Cliente</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Tienda / Productos</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($asignados as $asig)
                    @php $p = $asig->pedido; @endphp
                    <tr data-asignacion-id="{{ $asig->id }}" data-pedido-id="{{ $p->id }}" data-lat="{{ $p->lat ?? '' }}" data-lng="{{ $p->lng ?? '' }}" data-direc="{{ e($p->direccion) }}">
                        <td><strong>#{{ sprintf('%06d', $asig->id) }}</strong></td>
                        <td>{{ optional($p->cliente)->name ?? 'Invitado' }}</td>
                        <td>{{ $p->telefono }}</td>
                        <td>{{ Str::limit($p->direccion, 60) }}</td>
                        <td>
                            @php
                                $groups = [];
                                foreach($p->detalles as $det) {
                                    $prod = $det->producto;
                                    $v = optional($prod)->vendedor;
                                    $store = optional($v)->nombre_negocio ?? optional(optional($v)->user)->name ?? 'Tienda desconocida';
                                    $groups[$store][] = $det;
                                }
                            @endphp
                            @foreach($groups as $storeName => $items)
                            <div class="tienda-grupo">
                                <div class="tienda-nombre">{{ $storeName }}</div>
                                <ul class="productos-lista">
                                    @foreach($items as $it)
                                    <li>{{ optional($it->producto)->nombre ?? 'Producto eliminado' }} × {{ $it->cantidad }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </td>
                        <td><strong>S/ {{ number_format($p->total, 2) }}</strong></td>
                        <td class="asign-state">
                            <span class="badge {{ strtolower($asig->estado) }}">
                                {{ ucfirst($asig->estado) }}
                            </span>
                        </td>
                        <td>
                            @php $estado = $asig->estado; @endphp
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                @if($estado === 'asignado')
                                <form class="ajax-action" action="{{ route('repartidor.por_recoger', $p->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn-sm btn-accion">
                                        <i class="fas fa-shopping-bag"></i> Ir a recoger
                                    </button>
                                </form>
                                @elseif($estado === 'por_recoger')
                                <form class="ajax-action" action="{{ route('repartidor.en_camino', $p->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn-sm btn-accion">
                                        <i class="fas fa-shipping-fast"></i> En camino
                                    </button>
                                </form>
                                @elseif(in_array($estado, ['en_ruta','en_camino']))
                                <form class="ajax-action" action="{{ route('repartidor.entregar', $p->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn-sm btn-accion">
                                        <i class="fas fa-check-circle"></i> Entregado
                                    </button>
                                </form>
                                @elseif($estado === 'entregado')
                                <span class="badge entregado">
                                    <i class="fas fa-check"></i> Entregado
                                </span>
                                @else
                                <form class="ajax-action" action="{{ route('repartidor.entregar', $p->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn-sm btn-accion">
                                        <i class="fas fa-check-circle"></i> Entregado
                                    </button>
                                </form>
                                @endif

                                <button class="btn-sm btn-ver ver-mapa" 
                                        data-lat="{{ $p->lat ?? '' }}" 
                                        data-lng="{{ $p->lng ?? '' }}" 
                                        data-direc="{{ e($p->direccion) }}">
                                    <i class="fas fa-map-marker-alt"></i> Ver
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-truck"></i>
            <p style="font-size: 1.125rem; margin-bottom: 0.5rem;">No tienes asignaciones activas</p>
            <p>Tus pedidos asignados aparecerán aquí</p>
        </div>
        @endif
    </div>
</div>

{{-- Modal para el mapa --}}
<div id="modal-mapa" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-titulo">Ubicación del Pedido</h3>
            <button class="modal-close" onclick="cerrarMapa()">×</button>
        </div>
        <div id="map-modal"></div>
    </div>
</div>

{{-- Modal para el mapa --}}
<div id="modal-mapa" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-titulo">Ubicación del Pedido</h3>
            <button class="modal-close" onclick="cerrarMapa()">×</button>
        </div>
        <div id="map-modal"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="" crossorigin=""></script>
<script>
  let miMapa = null;

  // Ver ubicación en mapa
  document.addEventListener('click', function(e) {
    if(e.target.closest('.ver-mapa')) {
      const btn = e.target.closest('.ver-mapa');
      const lat = btn.dataset.lat;
      const lng = btn.dataset.lng;
      const direc = btn.dataset.direc;
      
      if(lat && lng && lat != '' && lng != '') {
        document.getElementById('modal-mapa').style.display = 'flex';
        setTimeout(() => {
          if(miMapa) {
            miMapa.remove();
          }
          miMapa = L.map('map-modal').setView([parseFloat(lat), parseFloat(lng)], 15);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
          }).addTo(miMapa);
          L.marker([parseFloat(lat), parseFloat(lng)])
            .addTo(miMapa)
            .bindPopup(direc)
            .openPopup();
        }, 100);
      } else {
        alert('No hay coordenadas disponibles para este pedido.');
      }
    }
  });

  function cerrarMapa() {
    document.getElementById('modal-mapa').style.display = 'none';
    if(miMapa) {
      miMapa.remove();
      miMapa = null;
    }
  }

  // AJAX para formularios
  document.addEventListener('submit', function(e) {
    if(e.target.classList.contains('ajax-action')) {
      e.preventDefault();
      const form = e.target;
      const url = form.action;
      const method = form.method || 'POST';

      fetch(url, {
        method: method,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        },
        body: method === 'POST' ? new FormData(form) : null
      })
      .then(r => r.json())
      .then(data => {
        if(data.success) {
          alert(data.message || 'Operación exitosa');
          location.reload();
        } else {
          alert(data.message || 'Error en la operación');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Error al realizar la acción');
      });
    }
  });

  // Cerrar modal haciendo clic fuera
  document.getElementById('modal-mapa').addEventListener('click', function(e) {
    if(e.target === this) {
      cerrarMapa();
    }
  });
</script>
@endsection