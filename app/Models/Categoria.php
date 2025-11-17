<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     * (Laravel adivina 'categorias' correctamente, pero es bueno tenerlo)
     */
    protected $table = 'categorias';

    /**
     * Indica si el modelo debe tener campos de marca de tiempo.
     * ¡ESTA ES LA LÍNEA CLAVE!
     */
    public $timestamps = false;

    /**
     * Los atributos que se pueden asignar en masa.
     * (Esto es necesario para que Categoria::create() funcione)
     */
    protected $fillable = ['nombre'];
}