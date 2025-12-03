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
            $table->foreignId('question_id')->constrained('grammar_questions');
            $table->string('wrong_order', 50);
            $table->text('wrong_sentence');
            $table->string('wrong_image_url', 255);
            $table->timestamps();
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
