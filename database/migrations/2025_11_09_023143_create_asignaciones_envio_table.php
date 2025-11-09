<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ..._create_asignaciones_envio_table.php

public function up(): void
{
    Schema::create('asignaciones_envio', function (Blueprint $table) {
        $table->id();
        
        // El pedido que se va a asignar
        // Lo hacemos único para que un pedido solo se asigne una vez
        $table->foreignId('pedido_id')
              ->unique() 
              ->constrained('pedidos')
              ->onDelete('cascade');
              
        // El repartidor que lo entregará
        // Apunta a la 'user_id' de la tabla 'repartidores'
        $table->foreignId('repartidor_id')
              ->constrained('repartidores', 'user_id')
              ->onDelete('cascade');
        
        $table->string('estado')->default('asignado'); // Ej: asignado, recogido, en_ruta
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones_envio');
    }
};
