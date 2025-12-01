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
            $table->foreignId('game_id')->constrained('games');
            $table->foreignId('created_by_admin_id')->nullable()->constrained('users');
            $table->integer('stage_id');
            $table->string('note', 100)->nullable();
            $table->string('image_url', 255);
            $table->string('word', 100);
            $table->integer('part_of_speech'); // 1=noun,2=verb,3=adj,4=other
            $table->timestamps();
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
