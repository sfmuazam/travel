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
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('thumbnail');
            $table->string('slug');
            $table->text('description');
            $table->date('departure_date');
            $table->date('arrival_date');

            $table->json('id_hotel')->nullable();

            $table->bigInteger('id_transportation')->unsigned()->nullable();
            $table->bigInteger('id_catering')->unsigned()->nullable();
            $table->bigInteger('id_airline')->unsigned()->nullable();

            $table->foreign('id_transportation')->references('id')->on('transportations');
            $table->foreign('id_catering')->references('id')->on('caterings');
            $table->foreign('id_airline')->references('id')->on('airlines');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_packages');
    }
};
