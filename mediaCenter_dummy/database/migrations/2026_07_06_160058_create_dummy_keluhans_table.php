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
        Schema::create('dummy_keluhans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('judul');
            $table->text('keluhan')->nullable();
            $table->text('konten')->nullable();
            $table->date('tanggal_keluhan')->nullable();

            $table->unsignedBigInteger('kecamatan_id')->nullable();
            $table->unsignedBigInteger('kelurahan_id')->nullable();
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->unsignedBigInteger('topik_id')->nullable();
            $table->unsignedBigInteger('instansi_id')->nullable();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();

            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('alamat')->nullable();

            $table->string('sosial_media')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('nomor_telepon')->nullable();
            $table->string('nama')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dummy_keluhans');
    }
};
