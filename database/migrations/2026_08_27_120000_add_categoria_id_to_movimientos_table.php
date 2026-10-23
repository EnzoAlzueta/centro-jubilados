<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Agrega categoria_id a movimientos con FK a la tabla categoria.
     *
     * La creación de la columna va en una migración aparte (no se modifica
     * 2026_02_14_205146_create_movimientos_table.php) para que las bases
     * existentes — que ya corrieron esa migración — también reciban la
     * columna al ejecutar `php artisan migrate`.
     */
    public function up(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->foreignId('categoria_id')
                ->nullable()
                ->after('monto')
                ->constrained('categoria');
        });
    }

    public function down(): void
    {
        Schema::table('movimientos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('categoria_id');
        });
    }
};
