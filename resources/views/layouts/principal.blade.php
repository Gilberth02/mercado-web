<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('titulo','Mercado Web')</title>

  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@500;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <!-- FontAwesome 5 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
    .menu a i { color: #059669; font-size: 1.1rem; margin-right: 4px; }
    
    /* Botón hamburguesa */
    .menu-toggle {
      display: none;
      background: transparent;
      border: none;
      font-size: 1.5rem;
      color: #059669;
      cursor: pointer;
      padding: 0.5rem;
      margin-left: auto;
    }
    
    /* Responsive navbar */
    @media (max-width: 768px) {
      .menu-toggle {
        display: block;
      }
      
      .menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 280px;
        height: 100vh;
        background: white;
        box-shadow: -2px 0 8px rgba(0,0,0,0.1);
        display: flex !important;
        flex-direction: column !important;
        align-items: stretch !important;
        flex-wrap: nowrap !important;
        justify-content: flex-start !important;
        padding: 4rem 1.5rem 1.5rem;
        transition: right 0.3s ease;
        z-index: 1000;
        overflow-y: auto;
      }
      
      .menu.active {
        right: 0;
      }
      
      .menu a {
        width: 100%;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        display: block;
      }
      
      .menu .linea {
        width: 100%;
        height: 1px;
        background: #e5e7eb;
        margin: 0.5rem 0;
      }
      
      .menu .carrito {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        position: relative;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        color: #333;
        font-weight: 500;
      }
      
      .menu .carrito .bola {
        position: static;
        margin-left: auto;
        background: #059669;
        color: #fff;
      }
      
      .menu form {
        width: 100%;
        display: block;
      }
      
      .menu .boton_login {
        width: 100%;
        text-align: center;
        margin-top: 0.5rem;
        display: block;
        padding: 1rem;
      }
      
      .barra_centro {
        display: flex;
        align-items: center;
      }
    }
    
    /* Overlay para cerrar menú */
    .menu-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 999;
    }
    
    .menu-overlay.active {
      display: block;
    }
    
    /* Botón cerrar dentro del menú */
    .menu-close {
      display: none;
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: transparent;
      border: none;
      font-size: 1.5rem;
      color: #6b7280;
      cursor: pointer;
      padding: 0.5rem;
    }
    
    @media (max-width: 768px) {
      .menu-close {
        display: block;
      }
    }
  </style>
</head>
<body>

  <!-- ===== barra ===== -->
  <header class="barra">
    <div class="contenedor barra_centro">
      <a class="marca" href="{{ url('/') }}">Mercado Web</a>
      
      <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
        <i class="fas fa-bars"></i>
      </button>

      <div class="menu-overlay" id="menuOverlay"></div>
      
      <nav class="menu" id="menu">
        <button class="menu-close" id="menuClose" aria-label="Cerrar menú">
          <i class="fas fa-times"></i>
        </button>
        <a href="{{ url('/')}}#inicio" class="activo"><i class="fas fa-home"></i> Inicio</a>
        <a href="{{ route('tienda.index') }}"><i class="fas fa-store"></i> Tienda</a>

        @guest
          <a href="{{ route('login') }}"><i class="fas fa-user"></i> Cliente</a>
          <a href="{{ route('login') }}"><i class="fas fa-user-tie"></i> Vendedor</a>
          <a href="{{ route('login') }}"><i class="fas fa-shipping-fast"></i> Delivery</a>
          {{-- Carrito justo después de Delivery para invitados --}}
          <a href="{{ route('cart.index') }}" class="carrito" aria-label="Carrito">
            <i class="fas fa-shopping-cart"></i>
            <span class="bola">{{ session('cart') ? count(session('cart')) : 0 }}</span>
          </a>
          <span class="linea"></span>
        @else
          {{-- Enlaces para usuarios autenticados --}}
          <a href="{{ route('cliente.redirect') }}"><i class="fas fa-user"></i> Cliente</a>

          @if(Auth::user()->vendedor)
            <a href="{{ route('vendedor.panel') }}"><i class="fas fa-user-tie"></i> Vendedor</a>
          @else
            <a href="{{ route('cliente.redirect', ['open' => 'vendedor']) }}"><i class="fas fa-user-tie"></i> Quiero ser Vendedor</a>
          @endif

          @if(Auth::user()->repartidor)
            <a href="{{ route('repartidor.panel') }}"><i class="fas fa-shipping-fast"></i> Delivery</a>
          @else
            <a href="{{ route('cliente.redirect', ['open' => 'delivery']) }}"><i class="fas fa-shipping-fast"></i> Registro Delivery</a>
          @endif
          {{-- Carrito justo después de Delivery para autenticados --}}
          <a href="{{ route('cart.index') }}" class="carrito" aria-label="Carrito">
            <i class="fas fa-shopping-cart"></i>
            <span class="bola">{{ session('cart') ? count(session('cart')) : 0 }}</span>
          </a>
          <span class="linea"></span>

          @if(Auth::user()->roles && Auth::user()->roles->contains('nombre', 'admin'))
            <a href="{{ route('admin.productos.index') }}"><i class="fas fa-user-shield"></i> Admin</a>
          @endif
        @endguest

        

        @guest
          <a href="{{ route('login') }}" class="boton_login">Login</a>
        @else
          <a href="{{ route('profile.edit') }}" class="boton_login" style="display: inline-flex; align-items: center; gap: 8px;">
            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;" onerror="this.onerror=null; this.src='{{ asset('Vista/img/avatar.png') }}'">
            <span>Perfil</span>
          </a>
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
        <h4>Acerca de</h4>
        <p>Este sitio web nace por la necesidad de un poblado de las provincias del Cusco<br>Quispichanchis Marcapata.</p>
      </div>
      <div class="columna">
        <h4>Nuestro objetivo</h4>
        <p>Ayudar a los pobladores a vender y comprar productos mediante este sitio web.</p>
      </div>
      <div class="columna">
        <h4>Direcciones</h4>
        <a href="{{ url('/') }}">Inicio</a>
        <a href="{{ route('cliente.redirect') }}">Cliente</a>
        <a href="{{ route('tienda.index') }}">Tienda</a>
        <a href="{{ route('profile.edit') }}">Perfil</a>
      </div>
    </div>
    <div class="copy">
      © 2025 por Mercado Web. Todos los derechos reservados.
    </div>
  </footer>
  
    {{-- Script para menú hamburguesa --}}
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menuToggle');
        const menuClose = document.getElementById('menuClose');
        const menu = document.getElementById('menu');
        const menuOverlay = document.getElementById('menuOverlay');
        
        function openMenu() {
          menu.classList.add('active');
          menuOverlay.classList.add('active');
          document.body.style.overflow = 'hidden';
        }
        
        function closeMenu() {
          menu.classList.remove('active');
          menuOverlay.classList.remove('active');
          document.body.style.overflow = '';
        }
        
        if (menuToggle) {
          menuToggle.addEventListener('click', openMenu);
        }
        
        if (menuClose) {
          menuClose.addEventListener('click', closeMenu);
        }
        
        if (menuOverlay) {
          menuOverlay.addEventListener('click', closeMenu);
        }
        
        // Cerrar menú al hacer clic en un enlace
        const menuLinks = menu.querySelectorAll('a');
        menuLinks.forEach(link => {
          link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
              closeMenu();
            }
          });
        });
      });
    </script>
    
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
