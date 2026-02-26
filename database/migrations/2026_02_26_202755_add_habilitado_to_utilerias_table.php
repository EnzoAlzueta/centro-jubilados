<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('utilerias', function (Blueprint $table) {
            $table->boolean('habilitado')->default(true)->after('stock_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('utilerias', function (Blueprint $table) {
            $table->dropColumn('habilitado');
        });
    }
};