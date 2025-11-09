<?php

// ..._create_usuario_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_roles', function (Blueprint $table) {
            // EL CAMBIO ESTÁ AQUÍ:
            // Ahora apunta a 'users' (la tabla correcta)
            // y el campo se llama 'user_id' (la convención de Laravel)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');

            // La llave primaria ahora usa 'user_id'
            $table->primary(['user_id', 'rol_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_roles');
    }
};