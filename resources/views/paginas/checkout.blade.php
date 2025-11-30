@extends('layouts.principal')
@section('titulo','Checkout')

@section('contenido')
<div class="contenedor">
    <h1>Finalizar Compra</h1>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Leaflet Control Geocoder CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <!-- Layout: mapa a la izquierda, formulario a la derecha -->
    <style>
        .checkout-grid { display:flex; gap:18px; align-items:flex-start; width:100%; box-sizing:border-box; }
        .checkout-map { flex: 0 0 56%; }
        .checkout-form { flex: 1 1 44%; }
        /* Keep horizontal (side-by-side) layout on mobile; only stack on very narrow screens */
        @media (max-width: 420px) {
            .checkout-grid { flex-direction: column; }
            .checkout-map, .checkout-form { flex: 1 1 100%; }
            #map { height: 300px !important; }
        }
        /* Slightly smaller map height to avoid pushing footer too far */
        #map { max-height: 420px; height: 380px; }
        @media (max-width: 640px) {
            .checkout-actions .btn-confirm,
            .checkout-actions .btn-back { width: 100%; }
            #use-location, #snap-street { width: 100%; }
        }
    </style>

    <div class="checkout-grid">
        <div class="checkout-map">
            <!-- Mapa para seleccionar ubicación -->
            <div id="map" style="width:100%;height:380px;margin-bottom:12px;border:1px solid #ddd;border-radius:6px"></div>
        </div>

        <div class="checkout-form">
            <form id="checkout-form" action="{{ route('cart.checkout.process') }}" method="POST">
        @csrf
        <div style="margin-bottom:12px">
            <label for="telefono">Teléfono</label><br>
            <input type="text" id="telefono" name="telefono" required inputmode="numeric" pattern="[0-9]{9}" placeholder="9 dígitos" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px" value="{{ old('telefono', auth()->check() ? auth()->user()->telefono : '') }}">
        </div>
        <div style="margin-bottom:12px">
            <label for="direccion">Dirección de entrega</label><br>
            <textarea id="direccion" name="direccion" required style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px" rows="4" placeholder="Calle, número, referencia..."></textarea>
            <div style="margin-top:8px;display:flex;gap:8px;align-items:center">
                <button type="button" id="use-location" style="background:#0b5ed7;color:#fff;padding:10px 12px;border-radius:8px;border:none;">Usar mi ubicación</button>
                <button type="button" id="snap-street" style="background:#6c757d;color:#fff;padding:10px 12px;border-radius:8px;border:none">Ajustar a la calle</button>
                <span id="loc-status" style="font-size:90%;color:#666"></span>
            </div>
            <input type="hidden" id="lat" name="lat">
            <input type="hidden" id="lng" name="lng">
        </div>

        <div class="checkout-actions" style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap">
            <button type="submit" class="btn-confirm" style="background:#1ca69a;color:#fff;padding:12px 16px;border-radius:8px;border:none;flex:1 1 280px">Confirmar compra</button>
            <a href="{{ route('cart.index') }}" class="btn-back" style="display:inline-block;background:#f3f4f6;color:#374151;padding:12px 16px;border-radius:8px;border:1px solid #e5e7eb;flex:1 1 280px;text-align:center;text-decoration:none">Volver al carrito</a>
        </div>
    </form>

    @if ($errors->any())
        <div style="margin-top:12px;color:red">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div style="margin-top:12px;color:green">{{ session('success') }}</div>
    @endif

</div>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Leaflet Control Geocoder (search) -->
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const btn = document.getElementById('use-location');
    const status = document.getElementById('loc-status');
    const direccion = document.getElementById('direccion');
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    if (!btn) return;
    // Inicializar mapa
    let map;
    let marker;

    function initMap(lat, lng) {
        if (!map) {
            map = L.map('map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
        } else {
            map.setView([lat, lng], 15);
        }

        if (marker) {
            marker.setLatLng([lat, lng]);
        } else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            marker.on('dragend', async function(e){
                const pos = marker.getLatLng();
                latInput.value = pos.lat;
                lngInput.value = pos.lng;
                // reverse geocode
                await reverseGeocode(pos.lat, pos.lng);
            });
        }
    }

    async function reverseGeocode(lat, lon) {
        try {
            status.textContent = 'Obteniendo dirección...';
            const resp = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}&addressdetails=1`);
            if (!resp.ok) throw new Error('Error en geocoding');
            const data = await resp.json();
            if (data && data.display_name) {
                direccion.value = data.display_name;
                status.textContent = 'Dirección actualizada.';
            } else {
                status.textContent = 'No se encontró dirección.';
            }
        } catch (err) {
            console.error(err);
            status.textContent = 'Error obteniendo dirección.';
        }
    }

    // Intentar inicializar mapa con valores si ya existen
    let initialLat = latInput && latInput.value ? parseFloat(latInput.value) : null;
    let initialLng = lngInput && lngInput.value ? parseFloat(lngInput.value) : null;
    if (initialLat && initialLng) {
        initMap(initialLat, initialLng);
    } else {
        // Default: mostrar mapa centrado en Cusco, Perú
        initMap(-13.532, -71.9675);
    }

    // Añadir control de búsqueda (geocoder) si está disponible
    try {
        if (typeof L !== 'undefined' && typeof L.Control !== 'undefined' && typeof L.Control.Geocoder !== 'undefined') {
            const geocoder = L.Control.geocoder({ defaultMarkGeocode: false }).addTo(map);
            geocoder.on('markgeocode', function(e) {
                const latlng = e.geocode.center;
                if (!marker) {
                    initMap(latlng.lat, latlng.lng);
                } else {
                    marker.setLatLng(latlng);
                    map.setView(latlng, 16);
                }
                latInput.value = latlng.lat;
                lngInput.value = latlng.lng;
                direccion.value = e.geocode.name || e.geocode.html || '';
            });
        }
    } catch (err) {
        console.warn('Geocoder no cargado o error al inicializar:', err);
    }

    // Permitir hacer click en el mapa para colocar marcador
    map.on && map.on('click', function(e){
        const latlng = e.latlng;
        if (!marker) {
            marker = L.marker([latlng.lat, latlng.lng], { draggable: true }).addTo(map);
            marker.on('dragend', async function(){
                const pos = marker.getLatLng();
                latInput.value = pos.lat;
                lngInput.value = pos.lng;
                await reverseGeocode(pos.lat, pos.lng);
            });
        } else {
            marker.setLatLng(latlng);
        }
        latInput.value = latlng.lat;
        lngInput.value = latlng.lng;
        reverseGeocode(latlng.lat, latlng.lng);
    });

    // Snap to street button
    const snapBtn = document.getElementById('snap-street');
    if (snapBtn) {
        snapBtn.addEventListener('click', async function(){
            if (!marker) {
                status.textContent = 'Coloca primero el marcador en el mapa.';
                return;
            }
            status.textContent = 'Ajustando al tramo/ubicación más cercano...';
            const pos = marker.getLatLng();
            try {
                // Usamos reverse geocoding para obtener la ubicación más representativa
                const resp = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${pos.lat}&lon=${pos.lng}&addressdetails=1`);
                if (!resp.ok) throw new Error('Error en geocoding');
                const data = await resp.json();
                if (data && data.lat && data.lon) {
                    const snappedLat = parseFloat(data.lat);
                    const snappedLon = parseFloat(data.lon);
                    marker.setLatLng([snappedLat, snappedLon]);
                    map.setView([snappedLat, snappedLon], 18);
                    latInput.value = snappedLat;
                    lngInput.value = snappedLon;
                    direccion.value = data.display_name || direccion.value;
                    status.textContent = 'Marcador ajustado a la vía más cercana.';
                } else {
                    status.textContent = 'No se pudo ajustar la posición.';
                }
            } catch (err) {
                console.error(err);
                status.textContent = 'Error al ajustar la posición.';
            }
        });
    }

    async function requestCurrentLocation(showStatus = true) {
        if (!navigator.geolocation) {
            if (showStatus) status.textContent = 'Geolocalización no soportada en este navegador.';
            return false;
        }

        if (showStatus) status.textContent = 'Solicitando ubicación...';
        btn.disabled = true;

        return new Promise((resolve) => {
            navigator.geolocation.getCurrentPosition(async function(pos){
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;
                latInput.value = lat;
                lngInput.value = lon;
                initMap(lat, lon);
                try {
                    await reverseGeocode(lat, lon);
                    if (showStatus) status.textContent = 'Dirección rellenada automáticamente.';
                } catch (err) {
                    if (showStatus) status.textContent = 'No se pudo obtener dirección automáticamente.';
                } finally {
                    btn.disabled = false;
                }
                resolve(true);
            }, function(err){
                console.error(err);
                if (showStatus) status.textContent = 'Permiso denegado o no se pudo obtener ubicación.';
                btn.disabled = false;
                resolve(false);
            }, { enableHighAccuracy: false, timeout: 10000, maximumAge: 0 });
        });
    }

    // Click del botón usa la misma función
    btn.addEventListener('click', function(){ requestCurrentLocation(true); });

    // Intentar obtener la ubicación automáticamente y mostrar el estado (pedirá permiso si hace falta)
    // Si el usuario ya dio permiso, rellenará el mapa; si no, el navegador pedirá permiso.
    setTimeout(function(){ requestCurrentLocation(true); }, 600);
});
</script>
@endsection
