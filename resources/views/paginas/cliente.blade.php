@extends('layouts.principal')
@section('titulo','Panel de Cliente')

@section('contenido')
  <div class="contenedor">
    <h1>Bienvenido a tu panel, {{ Auth::user()->name }}</h1>
    <p>Aquí verás tus pedidos y tu información de perfil.</p>

    <hr>
    
    <h2>Conviértete en parte de la comunidad</h2>
    <p>¿Tienes un negocio o quieres repartir? ¡Únete!</p>
    
    @if(!Auth::user()->vendedor)
      <a href="{{ route('vendedor.registro.show') }}" class="boton">
        Quiero ser Vendedor
      </a>
    @endif
    
    @if(!Auth::user()->repartidor)
      <a href="{{ route('repartidor.registro.show') }}" class="boton">
        Quiero ser Repartidor
      </a>
    @endif
  </div>
@endsection