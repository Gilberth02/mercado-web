{{-- paginas/repartidor-registro.blade.php --}}
@extends('layouts.principal')
@section('titulo','Registro de Repartidor')

@section('contenido')
  <div class="contenedor">
    <div class="registro-panel" style="max-width:1000px;margin:0 auto;display:flex;gap:30px;align-items:flex-start;">

      <div class="lado-imagen" style="flex:1;">
        <figure class="imagen" style="border-radius:8px;overflow:hidden;">
          <img src="{{ asset('Vista/img/repartidor.webp') }}" alt="Repartidor">
        </figure>
        <p class="lead" style="margin-top:12px;color:#444;">Completa los detalles de tu vehículo para empezar a recibir pedidos. Podrás administrar tus entregas desde tu panel.</p>
      </div>

      <div class="lado-form" style="flex:1;background:#fff;padding:22px;border-radius:8px;box-shadow:0 6px 20px rgba(0,0,0,0.06);">
        <h2 style="margin-top:0;margin-bottom:8px;">Registro de Repartidor</h2>
        <p style="margin:0 0 16px;color:#666;font-size:14px;">Ingresa la información de tu vehículo. No añadimos campos adicionales, solo mejoramos la presentación.</p>

        <form action="{{ route('repartidor.registro.store') }}" method="POST">
          @csrf

          <div class="campo" style="display:block;margin-bottom:14px;">
            <label class="etiqueta" style="display:block;margin-bottom:6px;">Vehículo (Ej: Moto, Bicicleta)</label>
            <input class="entrada" style="width:100%;box-sizing:border-box;" type="text" name="vehiculo" value="{{ old('vehiculo') }}" required>
            @error('vehiculo')
              <span class="error-laravel">{{ $message }}</span>
            @enderror
          </div>

          <div class="campo" style="display:block;margin-bottom:14px;">
            <label class="etiqueta" style="display:block;margin-bottom:6px;">Matrícula (o descripción)</label>
            <input class="entrada" style="width:100%;box-sizing:border-box;" type="text" name="matricula" value="{{ old('matricula') }}" required>
            @error('matricula')
              <span class="error-laravel">{{ $message }}</span>
            @enderror
          </div>

          <div style="display:flex;gap:12px;margin-top:18px;">
            <a href="{{ route('profile.edit') }}" class="boton_borde" style="flex:1;text-align:center;">Volver</a>
            <button type="submit" class="boton" style="flex:1;">Completar Registro</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection