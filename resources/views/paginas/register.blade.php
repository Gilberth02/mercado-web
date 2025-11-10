@extends('layouts.principal')
@section('titulo','Registro – Mercado Web')

{{-- Carga el mismo CSS de autenticación --}}
@section('estilos')
  <link rel="stylesheet" href="{{ asset('Vista/css/auth.css') }}">
@endsection

@section('contenido')
  <main class="area">
    <div class="contenedor">
      <div class="tarjeta">

        <section class="lado izq">
          <div class="caja">
            <h1 class="titu">Crear cuenta</h1>

            {{-- 
              CAMBIO 1: 
              - method="POST" (enviar datos)
              - action="{{ route('register') }}" (la ruta que Breeze creó para procesar el registro)
            --}}
            <form class="formulario" action="{{ route('register') }}" method="POST">
              
              {{-- CAMBIO 2: ¡Muy importante! Token de seguridad de Laravel --}}
              @csrf

              {{-- CAMBIO 3: Añadir 'name' a todos los inputs --}}
              <label class="campo">
                <span class="etiqueta">Nombre completo</span>
                {{-- name="name", value="{{ old('name') }}" para guardar el valor si hay un error --}}
                <input class="entrada" type="text" name="name" placeholder="tu nombre" value="{{ old('name') }}" required autofocus>
                {{-- CAMBIO 4: Mostrar errores de validación --}}
                @error('name')
                  <span class="error-laravel">{{ $message }}</span>
                @enderror
              </label>

              <label class="campo">
                <span class="etiqueta">Correo</span>
                {{-- name="email", value="{{ old('email') }}" --}}
                <input class="entrada" type="email" name="email" placeholder="Ingrese su correo a qui @gmail.com" value="{{ old('email') }}" required>
                @error('email')
                  <span class="error-laravel">{{ $message }}</span>
                @enderror
              </label>

              <label class="campo">
                <span class="etiqueta">Contraseña</span>
                {{-- name="password" --}}
                <input class="entrada" type="password" name="password" placeholder="mínimo 8 caracteres" required>
                @error('password')
                  <span class="error-laravel">{{ $message }}</span>
                @enderror
              </label>

              <label class="campo">
                <span class="etiqueta">Confirmar contraseña</span>
                {{-- name="password_confirmation" (así lo espera Breeze) --}}
                <input class="entrada" type="password" name="password_confirmation" placeholder="repite la contraseña" required>
              </label>

              {{-- 
                CAMBIO 5: Se eliminó el <select> "Tipo de usuario".
                Nuestra lógica es que todos se registran como 'cliente' primero.
              --}}

              <label class="acepta">
                {{-- name="terms" (Breeze puede validar esto) --}}
                <input type="checkbox" name="terms" required>
                <span>Acepto los términos y la política de privacidad</span>
                @error('terms')
                  <span class="error-laravel">{{ $message }}</span>
                @enderror
              </label>

              {{-- CAMBIO 6: type="submit" para enviar el formulario y quitar 'disabled' --}}
              <button type="submit" class="boton">Registrarme</button>

              <hr> {{-- Separador visual --}}

              {{-- CAMBIO 7: Botón de Google --}}
              <div class="campo-social">
                <a href="{{ route('google.redirect') }}" class="boton boton-google">
                  <img src="https://rotulosmatesanz.com/wp-content/uploads/2017/09/2000px-Google_G_Logo.svg_.png" alt="Google">
                  Registrarse con Google
                </a>
              </div>


              {{-- CAMBIO 8: Usar route('login') es más robusto que url('/login') --}}
              <p class="nota">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="enlace">Inicia sesión</a></p>
            </form>
          </div>
        </section>

        <aside class="lado der">
          <img class="foto" src="{{ asset('Vista/img/registro.png') }}" alt="Imagen lateral">
          <div class="capa"></div>
          <div class="forma uno"></div>
          <div class="forma dos"></div>
        </aside>

      </div>
    </div>
  </main>
@endsection