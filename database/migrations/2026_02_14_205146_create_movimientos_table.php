<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->string('concepto');
            $table->decimal('monto', 10, 2);
            $table->string('categoria')->default('manual'); // alquiler, cuota, manual, etc.
            $table->nullableMorphs('referencia'); // referencia_id y referencia_type
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};