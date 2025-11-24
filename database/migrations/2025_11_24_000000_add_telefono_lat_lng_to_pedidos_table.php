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
        Schema::table('pedidos', function (Blueprint $table) {
            if (!Schema::hasColumn('pedidos', 'telefono')) {
                $table->string('telefono')->nullable()->after('cliente_id');
            }
            if (!Schema::hasColumn('pedidos', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable()->after('telefono');
            }
            if (!Schema::hasColumn('pedidos', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable()->after('lat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (Schema::hasColumn('pedidos', 'lng')) {
                $table->dropColumn('lng');
            }
            if (Schema::hasColumn('pedidos', 'lat')) {
                $table->dropColumn('lat');
            }
            if (Schema::hasColumn('pedidos', 'telefono')) {
                $table->dropColumn('telefono');
            }
        });
    }
};
