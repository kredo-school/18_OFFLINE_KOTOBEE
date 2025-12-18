<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // if (!User::where('email', 'test@example.com')->exists()) {
        //     User::factory()->create([
        //         // 'name' => 'Test User',
        //         // 'email' => 'test@example.com',
        //     ]);
        // }



        // User::factory()->create([
        //     'name' => 'Test User',

        //     'email' => 'test@example.com',
        // ]);

        // GamesTable登録用シーダー呼び出し
        $this->call(GamesTableSeeder::class);
        // GameSettingsTable登録用シーダー呼び出し
        $this->call(GameSettingsTableSeeder::class);
        // Kana_questionsTable登録用シーダー呼び出し
        $this->call(Kana_questionsTableSeeder::class);
        $this->call(VocabQuestionSeeder::class);
    }
}
