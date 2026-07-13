<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('api_configs', function (Blueprint $table) {
            $table->json('request_mapping')->nullable()->after('description');
            $table->json('response_mapping')->nullable()->after('request_mapping');
            $table->string('response_mode')->default('standard')->after('response_mapping');
        });
    }

    public function down(): void
    {
        Schema::table('api_configs', function (Blueprint $table) {
            $table->dropColumn([
                'request_mapping',
                'response_mapping',
                'response_mode',
            ]);
        });
    }
}
;