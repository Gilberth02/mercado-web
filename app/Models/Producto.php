<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // Campos que se pueden asignar en masa
    protected $fillable = [
        'vendedor_id',
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'estado',
        'activo',
        'rechazo_motivo',
        'propuesta_edicion',
    ];

    // app/Models/Producto.php

    /**
     * La categoría a la que pertenece el producto.
     */
    public function categoria()
    {
        // Un Producto pertenece a una Categoría
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * El vendedor (dueño) del producto.
     */
    public function vendedor()
    {
        // Un Producto pertenece a un Vendedor
        return $this->belongsTo(Vendedor::class, 'vendedor_id', 'user_id');
    }

    // Relación: Un producto tiene muchas reseñas
public function resenas() {
    return $this->hasMany(Resena::class);
}

// Función auxiliar para obtener el promedio de estrellas
public function promedioCalificacion() {
    // Retorna el promedio o 0 si no hay calificaciones
    return round($this->resenas->avg('puntuacion'), 1) ?? 0;
}

}


