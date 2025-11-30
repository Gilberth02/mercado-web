<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('repartidores', function (Blueprint $table) {
            if (Schema::hasColumn('repartidores', 'telefono')) {
                $table->dropColumn('telefono');
            }
        });
    }

    public function down(): void
    {
        Schema::table('repartidores', function (Blueprint $table) {
            if (!Schema::hasColumn('repartidores', 'telefono')) {
                $table->string('telefono', 15)->nullable()->after('matricula');
            }
        });
    }
};