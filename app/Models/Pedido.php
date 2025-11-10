<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    // app/Models/Pedido.php


/**
 * El cliente que hizo el pedido.
 */
public function cliente()
{
    // Un Pedido pertenece a un Usuario (cliente)
    return $this->belongsTo(User::class, 'cliente_id');
}

/**
 * Los productos detallados en este pedido.
 */
public function detalles()
{
    // Un Pedido tiene muchos Detalles
    return $this->hasMany(DetallePedido::class, 'pedido_id');
}
}
