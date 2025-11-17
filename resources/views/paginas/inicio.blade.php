@extends('layouts.principal')
@section('titulo','Mercado Web – Inicio')

@section('contenido')
  <!-- ===== portada ===== -->
  <section id="inicio" class="portada">
    <div class="contenedor portada_centro">
      <figure class="foto">
        <img src="{{ asset('Vista/img/mercado.webp') }}" alt="Imagen principal">
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

        @guest
          <a href="{{ route('login') }}" class="boton">Comprar ahora</a>
        @else
          <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="boton">Cerrar sesión</button>
          </form>
        @endguest
      </div>
    </div>
  </section>

  <!-- ===== roles ===== -->
  <section id="roles" class="roles">
    <div class="contenedor">
      <div class="cajas">
        <div class="caja" id="cliente">
          <figure class="imagen">
            <img src="{{ asset('Vista/img/cliente.webp') }}" alt="Cliente">
          </figure>
          <div class="info">
            <h3>Cliente</h3>
            <p>Explora productos y realiza tus compras en línea.</p>
            @guest
              <a href="{{ route('login') }}" class="boton_borde">Ingresar</a>
            @else
              <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="boton_borde">Cerrar sesión</button>
              </form>
            @endguest
          </div>
        </div>

        <div class="caja" id="vendedor">
          <figure class="imagen">
            <img src="{{ asset('Vista/img/vendedor.webp') }}" alt="Vendedor">
          </figure>
          <div class="info">
            <h3>Vendedor</h3>
            <p>Publica productos y gestiona tus ventas.</p>
            @guest
              <a href="{{ route('login') }}" class="boton_borde">Ingresar</a>
            @else
              <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="boton_borde">Cerrar sesión</button>
              </form>
            @endguest
          </div>
        </div>

        <div class="caja" id="delivery">
          <figure class="imagen">
            <img src="{{ asset('Vista/img/repartidor.webp') }}" alt="Delivery">
          </figure>
          <div class="info">
            <h3>Delivery</h3>
            <p>Accede a pedidos y realiza entregas.</p>
            @guest
              <a href="{{ route('login') }}" class="boton_borde">Ingresar</a>
            @else
              <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" class="boton_borde">Cerrar sesión</button>
              </form>
            @endguest
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
