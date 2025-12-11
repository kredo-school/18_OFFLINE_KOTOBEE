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
        Schema::create('game_settings', function (Blueprint $table) {
            $table->id();

            // Parent game
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');

            // Game configuration
            $table->string('mode', 50);        // e.g. '60s-count', 'time-attack'
            $table->string('order_type', 50);  // e.g. 'regular', 'random'
            $table->string('script', 50);      // e.g. 'hiragana', 'katakana'
            $table->string('subtype', 50);     // e.g. 'seion', 'dakuon', 'youon'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_settings');
    }
};
