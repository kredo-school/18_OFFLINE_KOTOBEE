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
        Schema::create('grammar_questions', function (Blueprint $table) {
            $table->id();

            // Parent game
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');

            // Created by admin, nullable
            $table->foreignId('created_by_admin_id')->nullable()->constrained('users')->onDelete('set null');

            // Stage ID
            $table->integer('stage_id');

            // Note (optional)
            $table->string('note', 100)->nullable();

            // Problem image URL (illustration)
            $table->string('problem_image_url', 255);

            // Correct sentence
            $table->text('correct_sentence');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammar_questions');
    }
};
