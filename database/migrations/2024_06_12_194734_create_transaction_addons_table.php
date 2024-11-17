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
        Schema::create('transaction_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreignId('addons_id')->references('id')->on('add_on_products')->onDelete('cascade');
            $table->timestamps();
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_addons');
    }
};
