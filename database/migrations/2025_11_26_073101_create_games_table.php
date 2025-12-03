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
        Schema::create('games', function (Blueprint $table) {
            $table->id();

            $table->string('name', 100);
            $table->string('description', 100);

            // game_type: 1=kana, 2=vocabulary, 3=grammar
            $table->integer('game_type');

            $table->timestamps();

            // Optional: check constraint (MySQL 8+)
            // $table->check('game_type IN (1,2,3)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
