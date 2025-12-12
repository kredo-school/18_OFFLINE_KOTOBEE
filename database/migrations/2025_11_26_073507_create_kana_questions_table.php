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
        Schema::create('kana_questions', function (Blueprint $table) {
            $table->id();

            // Parent game
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');

            $table->string('kana_char', 12);

            // 1 = hiragana, 2 = katakana
            $table->integer('kana_type');

            // 1 = seion, 2 = dakuon, 3 = youon
            $table->integer('sound_type');

            $table->string('romaji', 20);

            $table->timestamps();

            // Optional: MySQL 8+ check constraints
            // $table->check('kana_type IN (1,2)');
            // $table->check('sound_type IN (1,2,3)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kana_questions');
    }
};
