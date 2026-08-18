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
        Schema::table('levels', function (Blueprint $table) {
            $table->integer('withdrawal_days')->default(7)->after('weekly_withdrawal_limit');
            $table->integer('referrals_required_for_withdrawal')->default(1)->after('withdrawal_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('levels', function (Blueprint $table) {
            $table->dropColumn(['withdrawal_days', 'referrals_required_for_withdrawal']);
        });
    }
};
