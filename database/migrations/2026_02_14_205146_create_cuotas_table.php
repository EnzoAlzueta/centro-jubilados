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
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained()->onDelete('cascade');
            $table->integer('mes');
            $table->integer('anio');
            $table->decimal('monto', 10, 2);
            $table->boolean('pagado')->default(false);
            $table->date('fecha_pago')->nullable();
            $table->timestamps();

            $table->unique(['socio_id', 'mes', 'anio']); // Un socio solo puede tener una cuota por mes/año
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};