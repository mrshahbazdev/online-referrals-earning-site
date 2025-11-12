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
        // Yeh command chalane ke liye `doctrine/dbal` package zaroori hai
        // composer require doctrine/dbal
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('type', [
                'commission', 'investment', 'withdrawal', 'task_reward', 'level_upgrade', 'refund'
            ])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
