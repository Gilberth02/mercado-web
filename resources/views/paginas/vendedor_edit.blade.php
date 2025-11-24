@extends('layouts.principal')
@section('titulo','Editar Producto')

@section('contenido')
  <div class="contenedor">
    <h1>Editar Producto</h1>

    @if(session('success'))
      <div class="alerta-exito" style="color: green; background: #e0ffe0; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        {{ session('success') }}
      </div>
    @endif

    <form action="{{ route('vendedor.productos.update', $producto) }}" method="POST" enctype="multipart/form-data" class="form-producto">
      @csrf
      @method('PATCH')

      <style>
        .form-producto { display:block; max-width:900px; margin-top:20px; }
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
      </style>

      <div class="form-grid">
        <div class="campo">
          <label class="etiqueta">Nombre del Producto</label>
          <input class="entrada" type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
          @error('nombre') <span class="error-laravel">{{ $message }}</span> @enderror
        </div>

        <div class="campo">
          <label class="etiqueta">Categoría</label>
          <select class="entrada" name="categoria_id" required>
            <option value="">Selecciona una categoría</option>
            @foreach ($categorias as $categoria)
              <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>{{ $categoria->nombre }}</option>
            @endforeach
          </select>
          @error('categoria_id') <span class="error-laravel">{{ $message }}</span> @enderror
        </div>

        <div class="campo">
          <label class="etiqueta">Precio (S/.)</label>
          <input class="entrada" type="number" step="0.01" name="precio" value="{{ old('precio', $producto->precio) }}" required>
          @error('precio') <span class="error-laravel">{{ $message }}</span> @enderror
        </div>

        <div class="campo">
          <label class="etiqueta">Stock (cantidad)</label>
          <input class="entrada" type="number" name="stock" value="{{ old('stock', $producto->stock) }}" required>
          @error('stock') <span class="error-laravel">{{ $message }}</span> @enderror
        </div>

        <div class="campo full">
          <label class="etiqueta">Descripción</label>
          <textarea class="entrada" name="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea>
          @error('descripcion') <span class="error-laravel">{{ $message }}</span> @enderror
        </div>

        <div class="campo">
          <label class="etiqueta">Imagen del Producto (opcional)</label>
          <div class="file-preview">
            <input class="entrada" id="imagen-input" type="file" name="imagen" accept="image/*">
            @if($producto->imagen)
              <img id="preview" class="preview-img" src="{{ asset('storage/' . $producto->imagen) }}" alt="Preview">
            @else
              <img id="preview" class="preview-img" src="{{ asset('Vista/img/placeholder.png') }}" alt="Preview">
            @endif
          </div>
          @error('imagen') <span class="error-laravel">{{ $message }}</span> @enderror
        </div>

        <div class="campo full">
          <label class="etiqueta">&nbsp;</label>
          <button type="submit" class="boton">Enviar cambios para revisión</button>
        </div>
      </div>

      <script>
        (function(){
          const input = document.getElementById('imagen-input');
          const preview = document.getElementById('preview');
          if(!input) return;
          input.addEventListener('change', function(e){
            const file = this.files && this.files[0];
            if(!file) { return; }
            const reader = new FileReader();
            reader.onload = function(ev){ preview.src = ev.target.result; };
            reader.readAsDataURL(file);
          });
        })();
      </script>

    </form>
  </div>
@endsection
