<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResenaRepartidor extends Model
{
    protected $table = 'resenas_repartidor';
    
    protected $fillable = ['user_id', 'repartidor_id', 'pedido_id', 'puntuacion', 'comentario'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function repartidor() {
        return $this->belongsTo(Repartidor::class, 'repartidor_id', 'user_id');
    }

    public function pedido() {
        return $this->belongsTo(Pedido::class);
    }
}
