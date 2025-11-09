<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ..._create_productos_table.php

public function up(): void
{
    Schema::create('productos', function (Blueprint $table) {
        $table->id(); // Llave primaria simple

        // Llave foránea a la tabla VENDEDORES
        // Apunta a la columna 'user_id' de la tabla 'vendedores'
        $table->foreignId('vendedor_id')
              ->constrained('vendedores', 'user_id')
              ->onDelete('cascade');

        // Llave foránea a la tabla CATEGORIAS
        $table->foreignId('categoria_id')
              ->constrained('categorias')
              ->onDelete('cascade');

        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->string('imagen')->nullable(); // Aquí guardaremos la RUTA al archivo
        $table->decimal('precio', 10, 2);
        $table->integer('stock')->default(0);
        $table->boolean('activo')->default(true);
        
        // Columna para la moderación
        $table->string('estado')->default('pendiente'); 

        $table->timestamps(); // crea 'creado_en' y 'actualizado_en'
    });
}
};
