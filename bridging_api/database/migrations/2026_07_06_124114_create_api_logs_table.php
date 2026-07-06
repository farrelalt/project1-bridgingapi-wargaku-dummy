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
         Schema::create('api_logs', function (Blueprint $table) {
        $table->id();
        $table->string('service_name')->nullable();
        $table->string('local_endpoint')->nullable();
        $table->string('target_endpoint')->nullable();
        $table->string('method')->nullable();
        $table->json('request_payload')->nullable();
        $table->json('response_payload')->nullable();
        $table->integer('status_code')->nullable();
        $table->boolean('is_success')->default(false);
        $table->text('error_message')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
