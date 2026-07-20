<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega baja lógica a barrios (columna habilitado), igual que calles,
 * sectores y utilerías. Antes el borrado era físico e irrecuperable.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barrios', function (Blueprint $table) {
            $table->boolean('habilitado')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('barrios', function (Blueprint $table) {
            $table->dropColumn('habilitado');
        });
    }
};
