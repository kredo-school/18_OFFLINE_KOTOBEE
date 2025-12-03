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
        Schema::create('game_results', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('game_id')->constrained('games');

            // Nullable: which combination was played (Kana game only)
            $table->foreignId('setting_id')->nullable()->constrained('game_settings');

            // Nullable: created by admin (separate questions by group admin)
            $table->foreignId('created_by_admin_id')->nullable()->constrained('users');

            $table->integer('score')->nullable();

            // Play time in hours (e.g., 2.7)
            $table->decimal('play_time', 4, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_results');
    }
};
