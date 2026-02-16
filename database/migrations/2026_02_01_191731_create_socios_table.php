<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->string('numero_socio')->unique();
            $table->string('dni')->unique();
            $table->string('nombre');
            $table->string('apellido');

            $table->foreignId('barrio_id')->constrained('barrios');
            $table->string('calle'); #preguntar si hacemos tabla o no.
            $table->string('altura');
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();

            $table->date('fecha_nacimiento');
            $table->date('fecha_alta')->default(now());
            $table->boolean('habilitado')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};
