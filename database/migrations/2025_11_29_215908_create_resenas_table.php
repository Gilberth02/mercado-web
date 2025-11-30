<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('resenas', function (Blueprint $table) {
        $table->id();
        
        // ¿Quién califica?
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // ¿Qué producto califica?
        $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
        
        // La calificación (1 a 5 estrellas)
        $table->unsignedTinyInteger('puntuacion'); 
        
        // El comentario (opcional)
        $table->text('comentario')->nullable();
        
        $table->timestamps();

        // Índice único para evitar que un usuario califique el mismo producto dos veces
        $table->unique(['user_id', 'producto_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
