@extends('layouts.principal')
@section('titulo','Panel de Repartidor')

@section('contenido')
  <div class="contenedor">
    <h1>Panel de Repartidor (Delivery)</h1>
    <p>Hola, {{ Auth::user()->name }}.</p>
    <p>Aquí verás los pedidos disponibles para entregar.</p>
  </div>
@endsection