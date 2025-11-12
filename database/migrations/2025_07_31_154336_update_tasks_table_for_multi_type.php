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
        Schema::table('tasks', function (Blueprint $table) {
            // 'task_type' ka naya column add karein
            $table->string('task_type')->default('youtube_watch')->after('title');
            // 'youtube_url' column ka naam badal kar 'task_url' karein
            $table->renameColumn('youtube_url', 'task_url');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
