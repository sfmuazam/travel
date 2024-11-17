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
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('fr_jumbotron_title')->nullable();
            $table->string('fr_jumbotron_subtitle')->nullable();
            $table->json('fr_promos')->nullable();
            $table->json('fr_gallery')->nullable();
            $table->json('fr_testimonial')->nullable();
            $table->json('fr_company_advantages')->nullable();
            $table->boolean('fr_gallery_status')->default(0);
            $table->boolean('fr_testimonial_status')->default(0);
            $table->boolean('fr_company_advantages_status')->default(0);
            $table->string('fr_gallery_title')->nullable();
            $table->string('fr_gallery_subtitle')->nullable();
            $table->string('fr_testimonial_title')->nullable();
            $table->string('fr_testimonial_subtitle')->nullable();
            $table->string('fr_company_advantages_title')->nullable();
            $table->string('fr_company_advantages_subtitle')->nullable();
            $table->string('fr_packages_title')->nullable();
            $table->string('fr_packages_subtitle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
