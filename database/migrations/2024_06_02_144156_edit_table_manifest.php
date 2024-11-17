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
            $table->string('middle_name')->nullable(); 
            $table->string('last_name');
            $table->string('father_name'); 
            $table->string('mother_name');

        });
        
        Schema::table('files_users', function (Blueprint $table) {
            $table->string('family_card');
            $table->date('passport_expiry_date');
            $table->string('file_family_card');
            $table->string('file_pas_photo_4x6');
            $table->string('file_marriage_book')->nullable();
            $table->string('file_covid_certificate')->nullable();
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
