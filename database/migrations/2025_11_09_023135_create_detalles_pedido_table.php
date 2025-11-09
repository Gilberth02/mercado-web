<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ..._create_detalles_pedido_table.php

public function up(): void
{
    Schema::create('detalles_pedido', function (Blueprint $table) {
        $table->id(); // Es más simple usar un ID simple aquí
        
        $table->foreignId('pedido_id')
              ->constrained('pedidos')
              ->onDelete('cascade');
              
        $table->foreignId('producto_id')
              ->constrained('productos')
              ->onDelete('cascade');
        
        $table->integer('cantidad');
        
        // Guardamos el precio al momento de la compra
        // por si el precio del producto cambia después
        $table->decimal('precio', 10, 2); 
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_pedido');
    }
};
