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
        Schema::table('manifests', function (Blueprint $table) {
            

        });
        
        Schema::table('files_users', function (Blueprint $table) {
            $table->dropColumn('visa');
            $table->dropColumn('recommendation_letter');
            $table->dropColumn('health_certificate');
            $table->dropColumn('file_visa');
            $table->dropColumn('file_health_certificate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('manifest', function (Blueprint $table) {
            //
        });
    }
};
