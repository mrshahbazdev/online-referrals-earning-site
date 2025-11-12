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
            $table->string('username', 100)->unique()->after('id');
            $table->string('mobile_number', 20)->unique()->nullable()->after('email');
            $table->string('first_name')->nullable()->after('password');
            $table->string('last_name')->nullable()->after('first_name');
            $table->text('address')->nullable()->after('last_name');
            $table->string('profile_image_url')->nullable()->after('address');
            $table->decimal('balance', 10, 2)->default(0.00)->after('profile_image_url');
            $table->string('referral_code', 10)->unique()->nullable()->after('balance');
            $table->foreignId('referred_by_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('level_id')->default(1)->constrained('levels');
            $table->enum('kyc_status', ['pending', 'approved', 'rejected'])->default('pending');

            // Drop the default 'name' column
            $table->dropColumn('name');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
