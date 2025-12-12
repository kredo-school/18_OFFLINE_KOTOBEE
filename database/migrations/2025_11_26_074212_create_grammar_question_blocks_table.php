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
        Schema::create('grammar_question_blocks', function (Blueprint $table) {
            $table->id();

            // Foreign key: parent grammar question
            $table->foreignId('question_id')->constrained('grammar_questions');

            $table->string('block_text', 100);

            // part_of_speech: 1=noun, 2=verb, 3=adj, 4=adv, 5=particle, 6=other
            $table->integer('part_of_speech');

            // Correct order number
            $table->integer('order_number');

            $table->timestamps();

            // Optional: MySQL 8+ check constraint
            // $table->check('part_of_speech IN (1,2,3,4,5,6)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammar_question_blocks');
    }
};
