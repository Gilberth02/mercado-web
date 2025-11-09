<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ..._create_pedidos_table.php

public function up(): void
{
    Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        
        // El cliente que hizo el pedido
        $table->foreignId('cliente_id')
              ->constrained('users')
              ->onDelete('cascade');
        
        $table->string('direccion');
        $table->string('estado')->default('pendiente'); // Ej: pendiente, en_camino, entregado
        $table->string('pago')->default('pendiente'); // Ej: pendiente, pagado, error
        $table->decimal('total', 10, 2);
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
