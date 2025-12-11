<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VocabQuestionSeeder extends Seeder
{
    public function run()
    {
        // Stage1 のみ画像あり
        $stage1Words = [
            ['ともだち', '/Users/hinatanishimoto/Downloads/ChatGPT Image 2025年12月5日 14_35_54.png'],
            ['せいと',   '/Users/hinatanishimoto/Downloads/ChatGPT Image 2025年12月5日 14_38_43.png'],
            ['せんせい', '/Users/hinatanishimoto/Downloads/ChatGPT Image 2025年12月5日 14_40_21.png'],
            ['わたし',   '/Users/hinatanishimoto/Desktop/スクリーンショット 2025-12-05 16.26.57.png'],
            ['にほん',   '/Users/hinatanishimoto/Downloads/9f2c5901929736861250abf8b8dd9f6f.jpg'],
        ];

        // ステージ2〜10（画像なし）
        $data = [
            2 => ['かぞく', 'ちち', 'はは', 'おねえさん', 'おとうと'],
            3 => ['ふでばこ', 'ペン', 'けしゴム', 'なふだ', 'さいふ'],
            4 => ['トイレ', 'とけい', 'ほん', 'ノート', 'かさ'],
            5 => ['バス', 'でんしゃ', 'くるま', 'ごはん', 'やさい'],
            6 => ['みます', 'ききます', 'かきます', 'そうじをします', 'べんきょうをします'],
            7 => ['サッカー', 'テニス', 'ゲーム', 'プール', 'えいがかん'],
            8 => ['チョコレート', 'ながい', 'みじかい', 'おおい', 'すくない'],
            9 => ['コンピュータ', 'ひだり', 'みぎ', 'うえ', 'した'],
            10 => ['しゃしん', 'かんたん', 'むずかしい', 'あつい', 'さむい'],
        ];

        // Stage 1 を画像付きで作成
        foreach ($stage1Words as $w) {
            DB::table('vocab_questions')->updateOrInsert(
                [
                    'word' => $w[0],
                    'stage_id' => 1,
                ],
                [
                    'game_id' => 2,
                    'created_by_admin_id' => null,
                    'note' => null,
                    'image_url' => '/images/' . $w[1], // ここだけ画像あり
                    'part_of_speech' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Stage 2〜10 を画像なしで作成
        foreach ($data as $stage => $words) {
            foreach ($words as $word) {
                DB::table('vocab_questions')->updateOrInsert(
                    [
                        'word' => $word,
                        'stage_id' => $stage,
                    ],
                    [
                        'game_id' => 2,
                        'created_by_admin_id' => null,
                        'note' => null,
                        'image_url' => null,
                        'part_of_speech' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
