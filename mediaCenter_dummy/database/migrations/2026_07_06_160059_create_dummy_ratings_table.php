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
        Schema::create('dummy_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('keluhan_id');
            $table->integer('responsivitas')->nullable();
            $table->integer('informasi')->nullable();
            $table->integer('tindak_lanjut')->nullable();
            $table->integer('keramahan')->nullable();
            $table->integer('kepuasan')->nullable();
            $table->text('kritik_saran')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dummy_ratings');
    }
};
