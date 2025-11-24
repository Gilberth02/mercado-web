@extends('layouts.principal')
@section('titulo','Registro de Vendedor')

@section('contenido')
  <div class="contenedor">
    <div class="registro-panel" style="max-width:1000px;margin:0 auto;display:flex;gap:30px;align-items:flex-start;">

      <div class="lado-imagen" style="flex:1;">
        <figure class="imagen" style="border-radius:8px;overflow:hidden;">
          <img src="{{ asset('Vista/img/vendedor.webp') }}" alt="Vendedor">
        </figure>
        <p class="lead" style="margin-top:12px;color:#444;">Crea tu perfil de vendedor para empezar a publicar productos y gestionar tus ventas. Completa el formulario y luego configura tu catálogo desde tu panel.</p>
      </div>

      <div class="lado-form" style="flex:1;background:#fff;padding:22px;border-radius:8px;box-shadow:0 6px 20px rgba(0,0,0,0.06);">
        <h2 style="margin-top:0;margin-bottom:8px;">Registro de Vendedor</h2>
        <p style="margin:0 0 16px;color:#666;font-size:14px;">Ingresa el nombre de tu negocio. No te preocupes, podrás editarlo luego desde tu perfil.</p>

        <form method="POST" action="{{ route('vendedor.registro.store') }}">
          @csrf

          <div class="campo">
            <label for="nombre_negocio">Nombre del negocio</label>
            <input id="nombre_negocio" name="nombre_negocio" type="text" value="{{ old('nombre_negocio') }}" required>
            @error('nombre_negocio')
              <div class="error">{{ $message }}</div>
            @enderror
          </div>

          <div style="display:flex;gap:12px;margin-top:18px;">
            <a href="{{ route('profile.edit') }}" class="boton_borde" style="flex:1;text-align:center;">Volver</a>
            <button type="submit" class="boton" style="flex:1;">Crear perfil de vendedor</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
