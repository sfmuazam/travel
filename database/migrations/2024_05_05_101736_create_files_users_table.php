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
        Schema::create('files_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manifest_id')->nullable()->references('id')->on('manifests')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->onDelete('cascade');
            $table->string('bpjs')->nullable();
            $table->string('ktp');
            $table->string('visa')->nullable();
            $table->string('passport')->nullable();
            $table->string('recommendation_letter')->nullable();
            $table->string('health_certificate')->nullable();
            $table->string('file_bpjs')->nullable();
            $table->string('file_ktp');
            $table->string('file_visa')->nullable();
            $table->string('file_passport')->nullable();
            $table->string('file_recommendation_letter')->nullable();
            $table->string('file_health_certificate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files_users');
    }
};
