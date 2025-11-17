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
    
    <h2>Añadir Nuevo Producto</h2>
    <p>Este producto pasará a revisión de un administrador antes de ser publicado.</p>

    <form action="{{ route('producto.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <label class="campo">
        <span class="etiqueta">Nombre del Producto</span>
        <input class="entrada" type="text" name="nombre" value="{{ old('nombre') }}" required>
        @error('nombre')
          <span class="error-laravel">{{ $message }}</span>
        @enderror
      </label>

      <label class="campo">
        <span class="etiqueta">Descripción</span>
        <textarea class="entrada" name="descripcion" style="border-radius: 20px; height: 100px;">{{ old('descripcion') }}</textarea>
        @error('descripcion')
          <span class="error-laravel">{{ $message }}</span>
        @enderror
      </label>

      <label class="campo">
        <span class="etiqueta">Precio (S/.)</span>
        <input class="entrada" type="number" step="0.01" name="precio" value="{{ old('precio') }}" required>
        @error('precio')
          <span class"error-laravel">{{ $message }}</span>
        @enderror
      </label>

      <label class="campo">
        <span class="etiqueta">Stock (Cantidad)</span>
        <input class="entrada" type="number" name="stock" value="{{ old('stock') }}" required>
        @error('stock')
          <span class="error-laravel">{{ $message }}</span>
        @enderror
      </label>

      <label class="campo">
        <span class="etiqueta">Categoría</span>
        <select class="entrada" name="categoria_id" required>
            <option value="">Selecciona una categoría</option>
            
            {{-- Aquí cargamos las categorías desde el controlador --}}
            @foreach ($categorias as $categoria)
                <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                    {{ $categoria->nombre }}
                </option>
            @endforeach
            
        </select>
        @error('categoria_id')
          <span class="error-laravel">{{ $message }}</span>
        @enderror
      </label>

      <label class="campo">
        <span class="etiqueta">Imagen del Producto</span>
        <input class="entrada" type="file" name="imagen" required>
        @error('imagen')
          <span class"error-laravel">{{ $message }}</span>
        @enderror
      </label>

      <button type="submit" class="boton">Subir Producto</button>
    </form>

  </div>
@endsection