<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('game_id')->constrained('games');
            $table->foreignId('setting_id')->nullable()->constrained('game_settings');
            $table->foreignId('created_by_admin_id')->nullable()->constrained('users');
            $table->integer('vcab_stage_id')->nullable();
            $table->integer('gram_stage_id')->nullable();
            $table->integer('score')->nullable();
            $table->decimal('play_time', 6, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_results');
    }
};
