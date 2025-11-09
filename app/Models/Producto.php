<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    // app/Models/Producto.php

// ... (dentro de la clase Producto)

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
}


