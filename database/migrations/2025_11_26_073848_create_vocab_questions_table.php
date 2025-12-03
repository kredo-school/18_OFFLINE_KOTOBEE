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
        Schema::create('vocab_questions', function (Blueprint $table) {
            $table->id();

            // Parent game
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');

            // Created by admin, nullable
            $table->foreignId('created_by_admin_id')->nullable()->constrained('users')->onDelete('set null');

            // Stage ID
            $table->integer('stage_id');

            // Note (optional)
            $table->string('note', 100)->nullable();

            // Image URL
            $table->string('image_url', 255);

            // Word
            $table->string('word', 100);

            // Part of speech: 1=noun, 2=verb, 3=adj, 4=other
            $table->integer('part_of_speech');

            $table->timestamps();

            // Optional: MySQL 8+ check constraint
            // $table->check('part_of_speech IN (1,2,3,4)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocab_questions');
    }
};
