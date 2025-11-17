<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     */
    protected $table = 'vendedores'; // 1. Le decimos la tabla correcta

    /**
     * La llave primaria del modelo.
     * (Nuestra llave es 'user_id', no 'id')
     */
    protected $primaryKey = 'user_id'; // 2. Le decimos la llave primaria correcta

    /**
     * Indica si el modelo debe tener campos de marca de tiempo.
     * (Nuestra migración sí los tiene, así que lo dejamos en 'true')
     */
    public $timestamps = true;

    /**
     * Los atributos que se pueden asignar en masa.
     */
    protected $fillable = [
        'user_id',
        'nombre_negocio',
    ];

    /**
     * Obtiene el usuario (dueño) de este perfil de vendedor.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}