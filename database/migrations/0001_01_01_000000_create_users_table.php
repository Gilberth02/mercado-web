<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// ..._create_users_table.php

public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('telefono')->nullable(); // <-- MODIFICACIÓN 1
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password')->nullable(); // <-- MODIFICACIÓN 2
        $table->rememberToken();
        $table->foreignId('current_team_id')->nullable(); // <-- Esto es de Jetstream
        $table->string('profile_photo_path', 2048)->nullable(); // <-- Esto es de Jetstream
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
