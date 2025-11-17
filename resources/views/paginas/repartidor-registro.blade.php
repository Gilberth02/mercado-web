{{-- paginas/repartidor-registro.blade.php --}}
@extends('layouts.principal')
@section('titulo','Registro de Repartidor')

@section('contenido')
  <div class="contenedor">
    <h1>Formulario de Registro de Repartidor</h1>
    <p>Completa los datos de tu vehículo para empezar a repartir.</p>

    <form action="{{ route('repartidor.registro.store') }}" method="POST">
      @csrf
      
      <label class="campo">
        <span class="etiqueta">Vehículo (Ej: Moto, Bicicleta)</span>
        <input class="entrada" type="text" name="vehiculo" value="{{ old('vehiculo') }}" required>
        @error('vehiculo')
          <span class="error-laravel">{{ $message }}</span>
        @enderror
      </label>
      
      <label class="campo">
        <span class="etiqueta">Matrícula (o descripción)</span>
        <input class="entrada" type="text" name="matricula" value="{{ old('matricula') }}" required>
        @error('matricula')
          <span class="error-laravel">{{ $message }}</span>
        @enderror
      </label>
      
      <button type="submit" class="boton">Completar Registro</button>
    </form>
  </div>
@endsection