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
        Schema::create('financials', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['in', 'out']);
            $table->enum('status', ['pending', 'canceled', 'completed', 'failed'])->default('pending');
            $table->integer('amount');
            $table->bigInteger('id_transaction')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('total_price');
            $table->integer('qty');
            $table->foreignId('travel_package_id')->references('id')->on('travel_packages')->onDelete('cascade');
            $table->foreignId('travel_package_type_id')->references('id')->on('package_types')->onDelete('cascade');
            $table->foreignId('by_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('notes')->nullable();
            $table->enum('payment_status', ['full', 'partial']);
            $table->string('payment_method')->nullable();
            $table->decimal('dp_amount', 15, 2)->nullable();
            $table->decimal('remaining_amount', 15, 2)->nullable();
            $table->date('dp_due_date')->nullable();
            $table->timestamps();
        });

        Schema::table('financials', function (Blueprint $table) {
            $table->foreign('id_transaction')->references('id')->on('transactions')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financials');
        Schema::dropIfExists('transactions');
    }
};
