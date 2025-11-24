<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pedido;
use App\Models\Repartidor;

class AsignacionEnvio extends Model
{
    //
    protected $table = 'asignaciones_envio';

    protected $fillable = [
        'pedido_id',
        'repartidor_id',
        'estado',
    ];

    /**
     * Relación al pedido asignado.
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Relación al perfil de repartidor (la tabla `repartidores` usa `user_id` como PK).
     */
    public function repartidor(): BelongsTo
    {
        return $this->belongsTo(Repartidor::class, 'repartidor_id', 'user_id');
    }
}
