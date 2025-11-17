@extends('layouts.principal')
@section('titulo','Registro de Vendedor')

@section('contenido')
<div class="contenedor">
  <h1>Registro de Vendedor</h1>

  <form method="POST" action="{{ route('vendedor.registro.store') }}">
    @csrf

    <div class="campo">
      <label for="nombre_negocio">Nombre del negocio</label>
      <input id="nombre_negocio" name="nombre_negocio" type="text" value="{{ old('nombre_negocio') }}" required>
      @error('nombre_negocio')
        <div class="error">{{ $message }}</div>
      @enderror
    </div>

    <button type="submit" class="boton">Crear perfil de vendedor</button>
  </form>
</div>
@endsection
