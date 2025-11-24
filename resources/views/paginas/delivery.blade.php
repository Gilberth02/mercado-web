@extends('layouts.principal')
@section('titulo','Panel de Repartidor')

@section('contenido')
@php use Illuminate\Support\Str; @endphp
  <div class="contenedor">
    <h1>Panel de Repartidor (Delivery)</h1>
    <p>Hola, {{ Auth::user()->name }}.</p>

    @if(Auth::user() && Auth::user()->repartidor)
      <p>Estado: <strong id="repartidor-disponible">{{ Auth::user()->repartidor->disponible ? 'Disponible' : 'No disponible' }}</strong></p>
      <form class="ajax-action" action="{{ route('repartidor.toggle_disponible') }}" method="POST" style="display:inline">
        @csrf
        <button class="boton_borde">{{ Auth::user()->repartidor->disponible ? 'Marcar no disponible' : 'Marcar disponible' }}</button>
      </form>
    @else
      <p>No tienes perfil de repartidor. <a href="{{ route('repartidor.registro.show') }}" class="boton_borde">Registrarme</a></p>
    @endif

    <h2>Pedidos disponibles</h2>
    @if(isset($pendientes) && $pendientes->count())
      <table class="tabla">
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
              <td>{{ optional($pedido->cliente)->name ?? 'Invitado' }}</td>
              
              <td>{{ $pedido->telefono }}</td>
              <td>{{ Str::limit($pedido->direccion, 80) }}</td>
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
                  <div><strong>{{ $storeName }}</strong>
                    <ul style="margin:6px 0 10px 16px;">
                      @foreach($items as $it)
                        <li>{{ optional($it->producto)->nombre ?? 'Producto eliminado' }} — x{{ $it->cantidad }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endforeach
              </td>
              <td>{{ number_format($pedido->total, 2) }}</td>
              <td>
                <form class="ajax-action" action="{{ route('repartidor.asignar', $pedido->id) }}" method="POST" style="display:inline">
                  @csrf
                  <button class="boton">Reclamar</button>
                </form>
                <button class="boton_borde ver-mapa" 
                        data-lat="{{ $pedido->lat ?? '' }}" 
                        data-lng="{{ $pedido->lng ?? '' }}" 
                        data-direc="{{ e($pedido->direccion) }}">Ver</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>No hay pedidos disponibles en este momento.</p>
    @endif

    <h2>Mis asignaciones</h2>
    @if(isset($asignados) && $asignados->count())
      <table class="tabla">
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
              <td>{{ sprintf('#%06d', $asig->id) }}</td>
              <td>{{ optional($p->cliente)->name ?? 'Invitado' }}</td>
              <td>{{ $p->telefono }}</td>
              <td>{{ Str::limit($p->direccion, 80) }}</td>
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
                  <div><strong>{{ $storeName }}</strong>
                    <ul style="margin:6px 0 10px 16px;">
                      @foreach($items as $it)
                        <li>{{ optional($it->producto)->nombre ?? 'Producto eliminado' }} — x{{ $it->cantidad }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endforeach
              </td>
              <td>{{ number_format($p->total, 2) }}</td>
              <td class="asign-state">{{ $asig->estado }}</td>
              <td>
                {{-- Acciones según estado de la asignación --}}
                @php $estado = $asig->estado; @endphp
                @if($estado === 'asignado')
                  <form class="ajax-action" action="{{ route('repartidor.por_recoger', $p->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="boton">Ir a recoger</button>
                  </form>
                @elseif($estado === 'por_recoger')
                  <form class="ajax-action" action="{{ route('repartidor.en_camino', $p->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="boton">Marcar en camino</button>
                  </form>
                @elseif(in_array($estado, ['en_ruta','en_camino']))
                  <form class="ajax-action" action="{{ route('repartidor.entregar', $p->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="boton">Marcar entregado</button>
                  </form>
                @elseif($estado === 'entregado')
                  <span class="small">Entregado</span>
                @else
                  <form class="ajax-action" action="{{ route('repartidor.entregar', $p->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="boton">Marcar entregado</button>
                  </form>
                @endif

                <button class="boton_borde ver-mapa" 
                        data-lat="{{ $p->lat ?? '' }}" 
                        data-lng="{{ $p->lng ?? '' }}" 
                        data-direc="{{ e($p->direccion) }}">Ver</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>No tienes asignaciones activas.</p>
    @endif

  </div>
@endsection

@section('estilos')
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="" crossorigin="" />
  <style>
    /* Modal básico para mostrar el mapa */
    .modal {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.45);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 10000;
    }
    .modal-content {
      background: #fff;
      width: 90%;
      max-width: 900px;
      border-radius: 8px;
      padding: 12px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }
    .modal-close {
      background: transparent;
      border: none;
      font-size: 18px;
      float: right;
      cursor: pointer;
    }
    #map-modal { height: 420px; border-radius:6px; }
  </style>
@endsection

@section('scripts')
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    (function(){
      let map = null;
      let marker = null;
      const modal = document.createElement('div');
      modal.id = 'mapModal';
      modal.className = 'modal';
      modal.style.display = 'none';
      modal.innerHTML = `
        <div class="modal-content">
          <button class="modal-close">✕</button>
          <div id="map-modal"></div>
          <p id="map-address" class="small"></p>
        </div>`;
      document.body.appendChild(modal);

      function openModal(lat, lng, direccion){
        modal.style.display = 'flex';
        // crear mapa si no existe
        setTimeout(function(){
          if(!map){
            map = L.map('map-modal');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
          }

          const latNum = parseFloat(lat) || 0;
          const lngNum = parseFloat(lng) || 0;
          map.setView([latNum, lngNum], (latNum === 0 && lngNum === 0) ? 2 : 15);

          if(marker){ map.removeLayer(marker); marker = null; }
          marker = L.marker([latNum, lngNum]).addTo(map);
          if(direccion){ marker.bindPopup(direccion).openPopup(); document.getElementById('map-address').textContent = direccion; }
          setTimeout(()=> map.invalidateSize(), 200);
        }, 150);
      }

      function closeModal(){
        modal.style.display = 'none';
      }

      // Delegación de eventos para botones Ver
      document.addEventListener('click', function(e){
        const boton = e.target.closest('.ver-mapa');
        if(boton){
          const lat = boton.dataset.lat || '';
          const lng = boton.dataset.lng || '';
          const direc = boton.dataset.direc || '';
          openModal(lat, lng, direc);
        }

        if(e.target.closest('.modal-close')){ closeModal(); }
      });

      // cerrar al hacer click fuera del contenido
      modal.addEventListener('click', function(e){
        if(e.target === modal) closeModal();
      });
      
      /* --- AJAX state transitions --- */
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      async function postAction(url, form){
        const res = await fetch(url, {
          method: (form.method || 'POST').toUpperCase(),
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: new FormData(form)
        });
        return res.json();
      }

      document.addEventListener('submit', function(e){
        const form = e.target.closest('form.ajax-action');
        if (!form) return;
        e.preventDefault();
        const url = form.action;
        const tr = form.closest('tr');

        postAction(url, form).then(data => {
          if (!data) return;
          if (data.success) {
            // If this was an assign action, remove the row from pendientes
            if (url.includes('/asignar/')) {
              if (tr && tr.parentNode) tr.parentNode.removeChild(tr);
              alert(data.message || 'Asignado');
              return;
            }

            // For other transitions, update the state cell in the row
            if (tr) {
              const stateCell = tr.querySelector('.asign-state');
              if (stateCell && data.estado) stateCell.textContent = data.estado;

              // Replace action buttons according to new state
              const actionsCell = tr.querySelector('td:last-child');
              if (actionsCell && data.estado) {
                let html = '';
                if (data.estado === 'asignado') {
                  html = `<form class="ajax-action" action="/repartidor/por-recoger/${tr.dataset.pedidoId}" method="POST">@csrf<button class="boton">Ir a recoger</button></form>`;
                } else if (data.estado === 'por_recoger') {
                  html = `<form class="ajax-action" action="/repartidor/en-camino/${tr.dataset.pedidoId}" method="POST">@csrf<button class="boton">Marcar en camino</button></form>`;
                } else if (data.estado === 'en_camino' || data.estado === 'en_ruta') {
                  html = `<form class="ajax-action" action="/repartidor/entregar/${tr.dataset.pedidoId}" method="POST">@csrf<button class="boton">Marcar entregado</button></form>`;
                } else if (data.estado === 'entregado') {
                  html = `<span class="small">Entregado</span>`;
                }
                // Append map button always
                html += ` <button class="boton_borde ver-mapa" data-lat="${tr.dataset.lat||''}" data-lng="${tr.dataset.lng||''}" data-direc="${tr.dataset.direc||''}">Ver</button>`;
                // Replace
                actionsCell.innerHTML = html.replace('@csrf','<input type="hidden" name="_token" value="'+csrfToken+'">');
              }
            }

            // If toggling disponible, update label
            if (url.includes('toggle-disponible') && data.disponible !== undefined) {
              const label = document.getElementById('repartidor-disponible');
              if (label) label.textContent = data.disponible ? 'Disponible' : 'No disponible';
            }
          } else {
            alert(data.message || 'Error en la acción');
          }
        }).catch(err => {
          console.error(err);
          alert('Error comunicándose con el servidor');
        });
      });
    })();
  </script>
@endsection