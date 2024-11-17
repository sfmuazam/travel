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
        Schema::create('room_hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->string('room_type_title');
            $table->string('room_number');
            $table->integer('room_qty');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_hotels');
    }
};
