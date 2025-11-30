<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repartidor extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos.
     */
    protected $table = 'repartidores';

    /**
     * La llave primaria del modelo (en la migración es user_id).
     */
    protected $primaryKey = 'user_id';

    /**
     * Indicar que la clave primaria no es auto-incrementable.
     */
    public $incrementing = false;

    /**
     * Tipo de la llave primaria.
     */
    protected $keyType = 'int';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'user_id',
        'vehiculo',
        'matricula',
        'estado',
        'disponible',
    ];

    /**
     * Relación con el usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con las reseñas del repartidor.
     */
    public function resenas()
    {
        return $this->hasMany(ResenaRepartidor::class, 'repartidor_id', 'user_id');
    }

    /**
     * Calcula el promedio de puntuación del repartidor.
     */
    public function promedioCalificacion()
    {
        return $this->resenas()->avg('puntuacion') ?? 0;
    }
}
