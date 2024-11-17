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
        Schema::table('financials', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('financials', function (Blueprint $table) {
            $table->enum('status', ['pending', 'canceled', 'completed', 'dp_completed'])->default('pending');
        });

        Schema::table('branch_financials', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('branch_financials', function (Blueprint $table) {
            $table->enum('status', ['pending', 'canceled', 'completed', 'dp_completed'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financials', function (Blueprint $table) {
            //
        });
    }
};
