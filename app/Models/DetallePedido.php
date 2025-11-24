<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    // La migración crea la tabla `detalles_pedido` (plural distinto), indicamos el nombre explícitamente
    protected $table = 'detalles_pedido';
    protected $fillable = ['pedido_id','producto_id','cantidad','precio'];

    /**
     * Relación al producto de este detalle.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
