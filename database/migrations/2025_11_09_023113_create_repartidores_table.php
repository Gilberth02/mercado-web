<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ..._create_repartidores_table.php

public function up(): void
{
    Schema::create('repartidores', function (Blueprint $table) {
        // Misma lógica que en vendedores
        $table->foreignId('user_id')
              ->primary()
              ->constrained('users')
              ->onDelete('cascade');
        
        $table->string('vehiculo');
        $table->string('matricula');
        $table->boolean('disponible')->default(false);
        $table->timestamps();
    });
}
};
