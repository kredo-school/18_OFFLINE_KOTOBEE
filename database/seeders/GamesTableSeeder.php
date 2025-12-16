<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('games')->insert([
            [
                'name' => 'Kana Game',
                'description' => 'Practice the Hiragana and Katakana words.',
                'game_type' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),                
            ],
            [
                'name' => 'Vocabrary Game',
                'description' => 'Practice basic vocabulary words.',
                'game_type' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),                  
            ],
            [
                'name' => 'Grammar Game',
                'description' => 'Practice basic Japanese grammar using example sentences.',
                'game_type' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),                  
            ]            
        ]);
    }
}
