@extends('layouts.principal')
@section('titulo','Login – Mercado Web')

@section('estilos')
  <link rel="stylesheet" href="{{ asset('Vista/css/auth.css') }}">
@endsection

@section('contenido')
  <main class="area">
    <div class="contenedor">
      <div class="tarjeta">

        <section class="lado izq">
          <div class="caja">
            <h1 class="titu">Iniciar sesión</h1>

            <form class="formulario" action="{{ route('login') }}" method="POST">
              @csrf

              <label class="campo">
                <span class="etiqueta">Correo</span>
                <input class="entrada" type="email" name="email" placeholder="Ingrese su correo a qui @gmail.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                  <span class="error-laravel">{{ $message }}</span>
                @enderror
              </label>

              <label class="campo">
                <span class="etiqueta">Contraseña</span>
                <input class="entrada" type="password" name="password" placeholder="contraseña" required>
                @error('password')
                  <span class="error-laravel">{{ $message }}</span>
                @enderror
              </label>

              <div class="fila">
                <label class="recuerda">
                  <input type="checkbox" name="remember"> <span>Recordarme</span>
                </label>
                <a href="{{ route('password.request') }}" class="olvido">¿Olvidaste la contraseña?</a>
              </div>

              <button type="submit" class="boton">Entrar</button>

              <hr>

              <div class="campo-social">
                <a href="{{ route('google.redirect') }}" class="boton boton-google">
                  <img src="https://rotulosmatesanz.com/wp-content/uploads/2017/09/2000px-Google_G_Logo.svg_.png" alt="Google">
                  Iniciar sesión con Google
                </a>
              </div>

              <p class="nota">¿No tienes cuenta? <a href="{{ route('register') }}" class="enlace">Regístrate</a></p>
            </form>
          </div>
        </section>

        <aside class="lado der">
          <img class="foto" src="{{ asset('Vista/img/login1.png') }}" alt="Imagen lateral">
          <div class="capa"></div>
          <div class="forma uno"></div>
          <div class="forma dos"></div>
        </aside>

      </div>
    </div>
  </main>
@endsection