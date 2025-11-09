<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // ..._create_vendedores_table.php

public function up(): void
{
    Schema::create('vendedores', function (Blueprint $table) {
        // Esta es la llave primaria y foránea al mismo tiempo.
        // Apunta a la tabla 'users'
        $table->foreignId('user_id')
              ->primary()
              ->constrained('users')
              ->onDelete('cascade');
        
        $table->string('nombre_negocio');
        $table->timestamps();
    });
}
};
