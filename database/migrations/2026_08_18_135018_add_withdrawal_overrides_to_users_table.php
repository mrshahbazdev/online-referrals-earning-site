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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('withdrawal_limit_override', 10, 2)->nullable()->after('balance');
            $table->integer('withdrawal_days_override')->nullable()->after('withdrawal_limit_override');
            $table->boolean('bypass_referral_requirement')->default(false)->after('withdrawal_days_override');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['withdrawal_limit_override', 'withdrawal_days_override', 'bypass_referral_requirement']);
        });
    }
};
