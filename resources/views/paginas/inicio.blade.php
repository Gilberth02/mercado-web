@extends('layouts.principal')
@section('titulo','Mercado Web – Inicio')

@section('contenido')
  <!-- ===== portada ===== -->
  <section id="inicio" class="portada">
    <div class="contenedor portada_centro">
      <figure class="foto">
        <img src="{{ asset('Vista/img/mercado.png') }}" alt="Imagen principal">
      </figure>

      <div class="texto">
        <div class="cinta">
          <span>BIENVENIDO</span>
          <span>A MERCADO WEB</span>
        </div>

        <h2 class="subtitulo">¿Qué hacemos?</h2>
        <p class="descripcion">
          Somos una plataforma para realizar compra, venta y delivery de productos
          <em>(Compra y vende aquí)</em>
        </p>

        <hr class="linea_centro">
        <h1 class="titulo">COMERCIO JUSTO</h1>

        <a href="#roles" class="boton">Comprar ahora</a>
      </div>
    </div>
  </section>

  <!-- ===== roles ===== -->
  <section id="roles" class="roles">
    <div class="contenedor">
      <div class="cajas">
        <div class="caja" id="cliente">
          <figure class="imagen">
            <img src="{{ asset('Vista/img/cliente.png') }}" alt="Cliente">
          </figure>
          <div class="info">
            <h3>Cliente</h3>
            <p>Explora productos y realiza tus compras en línea.</p>
            <a href="{{ url('/login') }}" class="boton_borde">Ingresar</a>
          </div>
        </div>

        <div class="caja" id="vendedor">
          <figure class="imagen">
            <img src="{{ asset('Vista/img/vendedor.png') }}" alt="Vendedor">
          </figure>
          <div class="info">
            <h3>Vendedor</h3>
            <p>Publica productos y gestiona tus ventas.</p>
            <a href="{{ url('/login') }}" class="boton_borde">Ingresar</a>
          </div>
        </div>

        <div class="caja" id="delivery">
          <figure class="imagen">
            <img src="{{ asset('Vista/img/repartidor.png') }}" alt="Delivery">
          </figure>
          <div class="info">
            <h3>Delivery</h3>
            <p>Accede a pedidos y realiza entregas.</p>
            <a href="{{ url('/login') }}" class="boton_borde">Ingresar</a>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
