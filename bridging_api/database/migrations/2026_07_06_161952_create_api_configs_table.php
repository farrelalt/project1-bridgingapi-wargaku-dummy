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
    Schema::create('api_configs', function (Blueprint $table) {
        $table->id();
        $table->string('service_name');
        $table->string('local_endpoint');
        $table->string('target_endpoint');
        $table->string('method');
        $table->string('status')->default('active');
        $table->boolean('is_restricted')->default(false);
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_configs');
    }
};
