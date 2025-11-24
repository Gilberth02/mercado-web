@extends('layouts.principal')
@section('titulo','Panel Cliente')

@section('contenido')
  <div class="contenedor">
    <h1>Panel Cliente</h1>

    <p class="lead">Hola, {{ Auth::user()->name }}. Desde aquí puedes transformar tu cuenta o gestionar tu información.</p>

    <div class="cajas" style="margin-top:24px;">
      <div class="caja" id="vendedor">
        <figure class="imagen">
          <img src="{{ asset('Vista/img/vendedor.webp') }}" alt="Vendedor">
        </figure>
        <div class="info">
          <h3>Vendedor</h3>
          <p>Vende tus productos en la tienda y gestiona tus ventas desde tu panel.</p>
          @if(Auth::user()->vendedor)
            <a href="{{ route('vendedor.panel') }}" class="boton_borde">Ir a mi panel</a>
          @else
            <a href="{{ route('vendedor.registro.show') }}" class="boton">Convertirme en Vendedor</a>
          @endif
        </div>
      </div>

      <div class="caja" id="delivery">
        <figure class="imagen">
          <img src="{{ asset('Vista/img/repartidor.webp') }}" alt="Delivery">
        </figure>
        <div class="info">
          <h3>Delivery</h3>
          <p>Recibe pedidos para repartir y gestiona tus entregas desde el panel de repartidor.</p>
          @if(Auth::user()->repartidor)
            <a href="{{ route('repartidor.panel') }}" class="boton_borde">Ir a mi panel</a>
          @else
            <a href="{{ route('repartidor.registro.show') }}" class="boton">Convertirme en Delivery</a>
          @endif
        </div>
      </div>

      <div class="caja" id="perfil">
        <figure class="imagen">
          <img src="{{ asset('Vista/img/cliente.webp') }}" alt="Perfil">
        </figure>
        <div class="info">
          <h3>Perfil</h3>
          <p>Actualiza tus datos de contacto, dirección y preferencias de la cuenta.</p>
          <a href="{{ route('profile.edit') }}" class="boton_borde">Editar mi perfil</a>
        </div>
      </div>
    </div>
  </div>
@endsection