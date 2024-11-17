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
        Schema::table('files_users', function (Blueprint $table) {
            $table->string('family_card')->nullable()->change();
            $table->date('passport_expiry_date')->nullable()->change();
            $table->string('file_family_card')->nullable()->change();
            $table->string('file_pas_photo_4x6')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files_users', function (Blueprint $table) {
            //
        });
    }
};
