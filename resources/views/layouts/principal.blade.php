<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('titulo','Mercado Web')</title>

  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <!-- CSS principal del sitio -->
  <link rel="stylesheet" href="{{ asset('Vista/css/style.css') }}">

  <!-- CSS adicional por página  -->
  @yield('estilos')
</head>
<body>

  <!-- ===== barra ===== -->
  <header class="barra">
    <div class="contenedor barra_centro">
      <a class="marca" href="{{ url('/') }}">Mercado Web</a>

      <nav class="menu">
        <a href="{{ url('/')}}#inicio" class="activo">Inicio</a>
        <a href="{{ url('/')}}#nosotros">Nosotros</a>
        <a href="{{ url('/')}}#cliente">Cliente</a>
        <a href="{{ url('/')}}#vendedor">Vendedor</a>
        <a href="{{ url('/')}}#delivery">Delivery</a>

        <span class="linea"></span>

        <a href="#" class="carrito" aria-label="Carrito">
          🛒<span class="bola">0</span>
        </a>

        <a href="{{ url('/login') }}" class="boton_login">Login</a>
      </nav>
    </div>
  </header>

  @yield('contenido')

  <!-- ===== pie ===== -->
  <footer id="contacto" class="pie">
    <div class="contenedor pie_centro">
      <div class="columna">
        <h4>Acerca de nosotros</h4>
        <p>Este sitio web nace por la necesidad de un poblado de las provincias del Cusco<br>Quispichanchis Marcapata.</p>
      </div>
      <div class="columna">
        <h4>Nuestro objetivo</h4>
        <p>Ayudar a los pobladores a vender y comprar productos mediante este sitio web.</p>
      </div>
      <div class="columna">
        <h4>Direcciones</h4>
        <a href="{{ url('/') }}">Inicio</a>
        <a href="{{ url('/')}}#nosotros">Nosotros</a>
        <a href="{{ url('/')}}#cliente">Cliente</a>
        <a href="{{ url('/')}}#vendedor">Vendedor</a>
        <a href="{{ url('/')}}#delivery">Delivery</a>
      </div>
    </div>
    <div class="copy">
      © 2025 por Mercado Web. Todos los derechos reservados.
    </div>
  </footer>

</body>
</html>
