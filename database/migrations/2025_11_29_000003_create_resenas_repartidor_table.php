<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resenas_repartidor', function (Blueprint $table) {
            $table->id();
            
            // ¿Quién califica?
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // ¿Qué repartidor califica?
            $table->foreignId('repartidor_id')->constrained('repartidores', 'user_id')->onDelete('cascade');
            
            // ¿Por qué pedido? (opcional pero recomendado)
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->onDelete('cascade');
            
            // La calificación (1 a 5 estrellas)
            $table->unsignedTinyInteger('puntuacion'); 
            
            // El comentario (opcional)
            $table->text('comentario')->nullable();
            
            $table->timestamps();

            // Índice único para evitar que un usuario califique el mismo repartidor múltiples veces por el mismo pedido
            $table->unique(['user_id', 'repartidor_id', 'pedido_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resenas_repartidor');
    }
};
