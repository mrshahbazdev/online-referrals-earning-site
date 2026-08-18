<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('referrals_required_for_withdrawal')->nullable()->default(null)->change();
        });
        
        // Reset existing users to NULL so they inherit from their Level
        DB::table('users')->update(['referrals_required_for_withdrawal' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('referrals_required_for_withdrawal')->default(1)->change();
        });
    }
};
