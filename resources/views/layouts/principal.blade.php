<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('titulo','Mercado Web')</title>

  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <!-- CSS principal del sitio -->
  <link rel="stylesheet" href="{{ asset('Vista/css/style.css') }}">

  <link rel="icon" href="{{ asset('favicon.ico') }}">
  <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">

  <!-- CSS adicional por página  -->
  @yield('estilos')
  <style>
    html, body { height: 100%; }
    body { display: flex; flex-direction: column; min-height: 100vh; }
    .site-main { flex: 1 0 auto; }
  </style>
</head>
<body>

  <!-- ===== barra ===== -->
  <header class="barra">
    <div class="contenedor barra_centro">
      <a class="marca" href="{{ url('/') }}">Mercado Web</a>

      <nav class="menu">
        <a href="{{ url('/')}}#inicio" class="activo">Inicio</a>
        <a href="{{ url('/')}}#nosotros">Nosotros</a>
        <a href="{{ route('tienda.index') }}">Tienda</a>

        @guest
          <a href="{{ url('/')}}#vendedor">Vendedor</a>
          <a href="{{ url('/')}}#delivery">Delivery</a>
        @else
          {{-- Enlaces para usuarios autenticados --}}
          {{-- Cliente se accede desde Perfil, se quita enlace directo del navbar --}}

          @if(Auth::user()->vendedor)
            <a href="{{ route('vendedor.panel') }}">Vendedor</a>
          @else
            <a href="{{ route('vendedor.registro.show') }}">Quiero ser Vendedor</a>
          @endif

          @if(Auth::user()->repartidor)
            <a href="{{ route('repartidor.panel') }}">Delivery</a>
          @else
            <a href="{{ route('repartidor.registro.show') }}">Registro Delivery</a>
          @endif

          @if(Auth::user()->roles && Auth::user()->roles->contains('nombre', 'admin'))
            <a href="{{ route('admin.productos.index') }}">Admin</a>
          @endif
        @endguest

        <span class="linea"></span>
{{-- Contar los items en la sesión --}}
        <a href="{{ route('cart.index') }}" class="carrito" aria-label="Carrito">
          🛒
          <span class="bola">{{ session('cart') ? count(session('cart')) : 0 }}</span>
        </a>

        @guest
          <a href="{{ route('login') }}" class="boton_login">Login</a>
        @else
          <a href="{{ route('profile.edit') }}" class="boton_login">Perfil</a>
          <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="boton_login">Cerrar sesión</button>
          </form>
        @endguest
      </nav>
    </div>
  </header>

  <main class="site-main">
    @yield('contenido')
  </main>

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
  
    {{-- SweetAlert2 CDN + Session toasts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        @if(session('status') === 'profile-updated')
          Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Perfil actualizado', showConfirmButton: false, timer: 2000});
        @endif

        @if(session('status') === 'password-updated')
          Swal.fire({toast: true, position: 'top-end', icon: 'success', title: 'Contraseña actualizada', showConfirmButton: false, timer: 2000});
        @endif

        @if(session('status') === 'verification-link-sent')
          Swal.fire({icon: 'success', title: 'Email enviado', text: 'Se envió el enlace de verificación.'});
        @endif

        @if(session('error'))
          var _swalErrorText = {!! json_encode(session('error')) !!};
          Swal.fire({icon: 'error', title: 'Error', text: _swalErrorText});
        @endif
      });
    </script>

    {{-- Sección para scripts específicos de cada vista --}}
    @yield('scripts')

</body>
</html>
