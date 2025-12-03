<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VocabQuestionSeeder extends Seeder
{
    public function run()
    {
        $words = [
            ['りんご', '76f3e9b7833f5e7d17440f56cb95fa0e.jpg'],
            ['バナナ', 'd1d845e056662f70cda59d1370bfe917.jpg'],
            ['ねこ',   'ed561b902c3e97ea06ef84b3d123a887.jpg'],
            ['いぬ',   'f5bd1c425123f5c30c0b75ea99a2d9f2.jpg'],
            ['くるま', '142d47e5fa461dd13b8724ba3a11a876.jpg'],
        ];

        // ステージ1〜5
        for ($stage = 1; $stage <= 5; $stage++) {
            foreach ($words as $w) {
                DB::table('vocab_questions')->updateOrInsert(
                    [
                        'word' => $w[0],
                        'stage_id' => $stage,
                    ],
                    [
                        'game_id' => 2,
                        'created_by_admin_id' => null,
                        'note' => null,
                        'image_url' => '/images/' . $w[1],
                        'part_of_speech' => 1, // noun
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
