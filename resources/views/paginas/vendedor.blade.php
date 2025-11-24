@extends('layouts.principal')
@section('titulo','Panel de Vendedor')

@section('contenido')
  <div class="contenedor">
    <h1>Panel de Vendedor</h1>
    <p>¡Felicidades, {{ Auth::user()->name }}!</p>
    
    @if(session('success'))
      <div class="alerta-exito" style="color: green; background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        {{ session('success') }}
      </div>
    @endif

    <hr>

    <button id="abrir-nuevo-producto" style="background:#1ca69a;color:#fff;padding:10px 14px;border-radius:8px;border:none;cursor:pointer;margin-bottom:18px">Añadir Nuevo Producto</button>

    <!-- Modal Nuevo Producto -->
    <div id="modal-nuevo-producto" style="display:none;position:fixed;inset:0;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);z-index:2500">
      <div style="background:#fff;padding:18px;border-radius:8px;max-width:900px;width:94%;box-shadow:0 8px 30px rgba(0,0,0,0.2);">
        <h2>Añadir Nuevo Producto</h2>
        <p>Este producto pasará a revisión de un administrador antes de ser publicado.</p>

        <form action="{{ route('producto.store') }}" method="POST" enctype="multipart/form-data" class="form-producto">
          @csrf

          <style>
            .form-producto { display:block; max-width:900px; margin-top:6px; }
            .form-grid { display:grid; grid-template-columns: 1fr; gap:12px; }
            @media(min-width:800px){ .form-grid{ grid-template-columns: 1fr 1fr; } }
            .campo { display:flex; flex-direction:column; }
            .etiqueta { font-weight:600; margin-bottom:6px; color:#333; }
            .entrada { padding:10px 12px; border:1px solid #ddd; border-radius:8px; font-size:14px; }
            textarea.entrada { min-height:120px; resize:vertical; }
            .full { grid-column:1/-1; }
            .file-preview { display:flex; gap:12px; align-items:flex-start; }
            .preview-img { width:140px; height:100px; object-fit:cover; border-radius:8px; border:1px solid #e6e6e6; background:#fafafa; }
            .boton { background:#1ca69a; color:#fff; padding:10px 18px; border-radius:8px; border:none; cursor:pointer; font-weight:600; display:inline-block; width:auto; }
            .boton:disabled{ opacity:0.6; cursor:not-allowed }
            .campo .boton { align-self:flex-start; }
            .error-laravel { color:#b00020; font-size:13px; margin-top:6px; }
          </style>

          <div class="form-grid">
            <div class="campo">
              <label class="etiqueta">Nombre del Producto</label>
              <input class="entrada" type="text" name="nombre" value="{{ old('nombre') }}" required>
              @error('nombre') <span class="error-laravel">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
              <label class="etiqueta">Categoría</label>
              <select class="entrada" name="categoria_id" required>
                <option value="">Selecciona una categoría</option>
                @foreach ($categorias as $categoria)
                  <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
                @endforeach
              </select>
              @error('categoria_id') <span class="error-laravel">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
              <label class="etiqueta">Precio (S/.)</label>
              <input class="entrada" type="number" step="0.01" name="precio" value="{{ old('precio') }}" required>
              @error('precio') <span class="error-laravel">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
              <label class="etiqueta">Stock (cantidad)</label>
              <input class="entrada" type="number" name="stock" value="{{ old('stock') }}" required>
              @error('stock') <span class="error-laravel">{{ $message }}</span> @enderror
            </div>

            <div class="campo full">
              <label class="etiqueta">Descripción</label>
              <textarea class="entrada" name="descripcion">{{ old('descripcion') }}</textarea>
              @error('descripcion') <span class="error-laravel">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
              <label class="etiqueta">Imagen del Producto</label>
              <div class="file-preview">
                <input class="entrada" id="imagen-input" type="file" name="imagen" accept="image/*" required>
                <img id="preview" class="preview-img" src="{{ asset('Vista/img/placeholder.png') }}" alt="Preview">
              </div>
              @error('imagen') <span class="error-laravel">{{ $message }}</span> @enderror
            </div>

            <div class="campo full">
              <label class="etiqueta">&nbsp;</label>
              <div style="display:flex;gap:8px">
                <button type="submit" class="boton">Subir Producto</button>
                <button id="cerrar-modal-nuevo" type="button" style="background:#6c757d;color:#fff;padding:10px 14px;border-radius:8px;border:none;cursor:pointer">Cancelar</button>
              </div>
            </div>
          </div>

          <script>
            (function(){
              const input = document.getElementById('imagen-input');
              const preview = document.getElementById('preview');
              if(!input) return;
              input.addEventListener('change', function(e){
                const file = this.files && this.files[0];
                if(!file) { preview.src = '{{ asset("Vista/img/placeholder.png") }}'; return; }
                const reader = new FileReader();
                reader.onload = function(ev){ preview.src = ev.target.result; };
                reader.readAsDataURL(file);
              });
            })();
          </script>

        </form>
      </div>
    </div>

    <script>
      (function(){
        const abrir = document.getElementById('abrir-nuevo-producto');
        const modal = document.getElementById('modal-nuevo-producto');
        const cerrar = document.getElementById('cerrar-modal-nuevo');
        if(abrir && modal){
          abrir.addEventListener('click', function(){ modal.style.display = 'flex'; });
        }
        if(cerrar && modal){
          cerrar.addEventListener('click', function(){ modal.style.display = 'none'; });
        }
        // Abrir modal si hay errores de validación (servidor)
        const hasErrors = <?php echo $errors->any() ? 'true' : 'false'; ?>;
        if(hasErrors && modal){ modal.style.display = 'flex'; }
      })();
    </script>
    
    {{-- Lista de productos del vendedor --}}
    <hr style="margin-top:30px;margin-bottom:20px;">
    <h2>Mis Productos</h2>
    @if($productos && $productos->count() > 0)
      <table style="width:100%;border-collapse:collapse;margin-top:12px">
        <thead>
          <tr style="background:#f5f5f5">
            <th style="padding:8px;border:1px solid #eee">Imagen</th>
            <th style="padding:8px;border:1px solid #eee">Nombre</th>
            <th style="padding:8px;border:1px solid #eee">Precio</th>
            <th style="padding:8px;border:1px solid #eee">Estado</th>
            <th style="padding:8px;border:1px solid #eee">Activo</th>
            <th style="padding:8px;border:1px solid #eee">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($productos as $p)
              <tr>
                <tr id="producto-row-{{ $p->id }}">
                <td style="padding:8px;border:1px solid #eee;width:110px">
                @if($p->imagen)
                  <img src="{{ asset('storage/' . $p->imagen) }}" alt="{{ $p->nombre }}" style="width:100px;height:70px;object-fit:cover;border-radius:6px;">
                @else
                  <div style="width:100px;height:70px;background:#f5f5f5;display:flex;align-items:center;justify-content:center;color:#999;border-radius:6px">No imagen</div>
                @endif
              </td>
              <td style="padding:8px;border:1px solid #eee">{{ $p->nombre }}</td>
              <td style="padding:8px;border:1px solid #eee">S/ {{ $p->precio }}</td>
              <td style="padding:8px;border:1px solid #eee">{{ ucfirst($p->estado) }}</td>
              <td class="activo-cell" style="padding:8px;border:1px solid #eee">{{ $p->activo ? 'Sí' : 'No' }}</td>
              <td style="padding:8px;border:1px solid #eee;width:160px">
                <form class="ajax-toggle" action="{{ route('vendedor.productos.toggle', $p) }}" method="POST" style="display:inline-block;margin:0;" data-product-id="{{ $p->id }}">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="toggle-btn" style="background:#0b5ed7;color:#fff;padding:6px 10px;border-radius:6px;border:none;cursor:pointer">{{ $p->activo ? 'Deshabilitar' : 'Habilitar' }}</button>
                </form>

                <a href="{{ route('vendedor.productos.edit', $p) }}" style="display:inline-block;margin-left:8px;background:#f0ad4e;color:#fff;padding:6px 10px;border-radius:6px;text-decoration:none">Editar</a>

                <form action="{{ route('vendedor.productos.destroy', $p) }}" method="POST" style="display:inline-block;margin-left:8px;" onsubmit="return confirm('¿Eliminar este producto? Esta acción no se puede deshacer.');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" style="background:#d9534f;color:#fff;padding:6px 10px;border-radius:6px;border:none;cursor:pointer">Eliminar</button>
                </form>
                @if($p->rechazo_motivo)
                  <button class="btn-motivo" data-motivo="{{ e($p->rechazo_motivo) }}" style="display:inline-block;margin-left:8px;background:#6c757d;color:#fff;padding:6px 10px;border-radius:6px;border:none;">Motivo</button>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>No has subido productos aún.</p>
    @endif

    {{-- Pedidos que contienen productos de este vendedor --}}
    <hr style="margin-top:30px;margin-bottom:20px;">
    <h2>Pedidos relacionados</h2>
    @if(isset($pedidos) && $pedidos->count() > 0)
      <div class="tabla_responsive">
        <table class="tabla">
          <thead>
            <tr>
              <th>Referencia</th>
              <th>Cliente</th>
              <th>Teléfono</th>
              <th>Productos (tuyos)</th>
              <th>Total</th>
              <th>Repartidor</th>
              <th>Estado</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pedidos as $pedido)
              @php
                // Filtrar solo los detalles que pertenecen a este vendedor
                $misDetalles = $pedido->detalles->filter(function($d){
                  return optional($d->producto)->vendedor_id === optional(Auth::user()->vendedor)->user_id;
                });
              @endphp
              <tr>
                <td>#{{ sprintf('%06d', $pedido->id) }}</td>
                <td>{{ optional($pedido->cliente)->name ?? 'Invitado' }}</td>
                <td>{{ $pedido->telefono }}</td>
                <td>
                  <ul style="list-style:none;padding-left:0;margin:0">
                    @foreach($misDetalles as $det)
                      <li>{{ optional($det->producto)->nombre }} x {{ $det->cantidad }}</li>
                    @endforeach
                  </ul>
                </td>
                <td>S/ {{ number_format($pedido->total, 2) }}</td>
                <td>
                  @if($pedido->asignacion && $pedido->asignacion->repartidor)
                    @php
                      $repUser = optional($pedido->asignacion->repartidor->user);
                      $repPhone = $repUser->telefono ?? null;
                      $telHref = $repPhone ? 'tel:' . preg_replace('/\s+/', '', $repPhone) : null;
                    @endphp
                    <div>{{ $repUser->name ?? 'Repartidor' }}</div>
                    @if($repPhone)
                      <div class="small">{{ $repPhone }}</div>
                      <div style="margin-top:6px">
                        <a href="{{ $telHref }}" class="boton_borde" style="padding:6px 10px;">Contactar repartidor</a>
                      </div>
                    @else
                      <div class="small">No tiene teléfono registrado</div>
                    @endif
                  @else
                    <span class="small">No asignado</span>
                  @endif
                </td>
                <td>{{ ucfirst($pedido->estado) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p>No hay pedidos relacionados con tus productos.</p>
    @endif

  </div>
@endsection

@section('scripts')
<script>
  (function(){
    const token = '{{ csrf_token() }}';

    document.querySelectorAll('.ajax-toggle').forEach(function(form){
      form.addEventListener('submit', function(e){
        e.preventDefault();
        if(!confirm('¿Estás seguro de cambiar el estado de este producto?')) return;

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
              const row = document.getElementById('producto-row-' + productId);
              if(row){
                const activoCell = row.querySelector('.activo-cell');
                if(activoCell) activoCell.textContent = data.activo ? 'Sí' : 'No';
                // actualizar texto del botón
                if(btn) btn.textContent = data.activo ? 'Deshabilitar' : 'Habilitar';
              }
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
</script>
<script>
  // Mostrar modal con motivo de rechazo
  (function(){
    document.addEventListener('click', function(e){
      const btn = e.target.closest('.btn-motivo');
      if(!btn) return;
      const motivo = btn.getAttribute('data-motivo') || '';
      // crear modal simple
      const modal = document.createElement('div');
      modal.style = 'position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,0.45);z-index:3000';
      modal.innerHTML = `
        <div style="background:#fff;padding:18px;border-radius:8px;max-width:640px;width:92%;box-shadow:0 8px 30px rgba(0,0,0,0.2)">
          <h3 style="margin-top:0">Motivo del rechazo</h3>
          <p style="white-space:pre-wrap">${motivo}</p>
          <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:12px">
            <button id="cerrar-motivo-btn" style="background:#6c757d;color:#fff;padding:8px 12px;border-radius:6px;border:none;">Cerrar</button>
          </div>
        </div>
      `;
      document.body.appendChild(modal);
      document.getElementById('cerrar-motivo-btn').addEventListener('click', function(){ document.body.removeChild(modal); });
    });
  })();
</script>
@endsection