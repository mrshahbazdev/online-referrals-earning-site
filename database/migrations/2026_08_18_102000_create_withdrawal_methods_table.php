<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdrawal_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default methods
        \Illuminate\Support\Facades\DB::table('withdrawal_methods')->insert([
            ['name' => 'USDT TRC20', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'JazzCash', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Easypaisa', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bank Transfer', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawal_methods');
    }
};
