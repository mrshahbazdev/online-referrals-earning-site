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
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            if (Schema::hasColumn('withdrawal_requests', 'wallet_address')) {
                // If wallet_address exists, rename it to account_number or just drop it if we are recreating
                // Let's drop it to recreate properly
                $table->dropColumn('wallet_address');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'method')) {
                $table->string('method')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'account_title')) {
                $table->string('account_title')->nullable()->after('method');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'account_number')) {
                $table->string('account_number')->nullable()->after('account_title');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('account_number');
            }
            if (!Schema::hasColumn('withdrawal_requests', 'reject_reason')) {
                $table->text('reject_reason')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->string('wallet_address')->nullable();
            $table->dropColumn(['method', 'account_title', 'account_number', 'bank_name', 'reject_reason']);
        });
    }
};
