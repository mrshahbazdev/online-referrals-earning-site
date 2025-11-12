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
        // Is command ko chalane ke liye `doctrine/dbal` package zaroori hai.
        // Agar install nahi hai to pehle `composer require doctrine/dbal` chalayein.
        Schema::table('users', function (Blueprint $table) {
            $table->enum('kyc_status', ['unverified', 'pending', 'approved', 'rejected'])
                ->default('unverified')->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])
                ->default('pending')->change();
        });
    }
};
