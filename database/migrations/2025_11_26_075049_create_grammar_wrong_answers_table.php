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
        Schema::create('grammar_wrong_answers', function (Blueprint $table) {
            $table->id();

            // Foreign key: parent grammar question
            $table->foreignId('question_id')->constrained('grammar_questions');

            $table->string('wrong_order', 50);    // e.g., '1,3,2,5,4'
            $table->text('wrong_sentence');
            $table->string('wrong_image_url', 255);

            // Optional: error_type (commented out)
            // $table->integer('error_type');

            $table->timestamps();

            // Optional: MySQL 8+ check constraint
            // $table->check('error_type IN (1,2,3,4)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grammar_wrong_answers');
    }
};
