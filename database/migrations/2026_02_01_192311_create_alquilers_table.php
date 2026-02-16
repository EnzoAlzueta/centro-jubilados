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
        Schema::create('alquileres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sector_id')->constrained('sectors');

            # Acá dependiendo si es socio o externo usamos uno u otro
            $table->foreignId('socio_id')->nullable()->constrained('socios');
            $table->string('solicitante_externo')->nullable();
            $table->string('dni_solicitante_externo')->nullable();

            $table->dateTime('fecha_evento');
            $table->string('tipo_evento');
            $table->decimal('precio', 10, 2);
            $table->decimal('seña_pagada', 10, 2)->default(0);
            $table->enum('estado', ['reservado', 'confirmado', 'cancelado', 'finalizado'])->default('reservado');
            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquilers');
    }
};
