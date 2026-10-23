<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demo_records', function (Blueprint $table) {
            $table->id();
            $table->string('table_name');
            $table->unsignedBigInteger('record_id');
            $table->unique(['table_name', 'record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_records');
    }
};
