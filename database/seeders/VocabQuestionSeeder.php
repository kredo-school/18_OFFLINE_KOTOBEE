<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VocabQuestionSeeder extends Seeder
{
    public function run()
    {
        // Stage1（名詞）
        $stage1Words = [
            ['ともだち', 'storage/images/game_images/vocabulary/friends_man.png', 1],
            ['せいと', 'storage/images/game_images/vocabulary/kaigi_shinken_school_gakuran.png', 1],
            ['せんせい', 'storage/images/game_images/vocabulary/job_teacher_man.png', 1],
            ['わたし', 'storage/images/game_images/vocabulary/スクリーンショット 2025-12-05 16.26.57', 1],
            ['にほん', 'storage/images/game_images/vocabulary/9f2c5901929736861250abf8b8dd9f6f.jpg', 1],
        ];

        // Stage2（名詞）
        $stage2Words = [
            ['かぞく', 'storage/images/game_images/vocabulary/名称未設定のデザイン.png', 1],
            ['ちち', 'storage/images/game_images/vocabulary/baby_dakkohimo_man.png', 1],
            ['はは', 'storage/images/game_images/vocabulary/39bf056a1a3f58b199426a90d3cb04ca.jpg', 1],
            ['おねえさん', 'storage/images/game_images/vocabulary/family_ane_otouto.png', 1],
            ['おとうと', 'storage/images/game_images/vocabulary/family_3_kyoudai.png', 1],
        ];

        // Stage3（名詞）
        $stage3Words = [
            ['ふでばこ', 'storage/images/game_images/vocabulary/bunbougu_fudebako.png', 1],
            ['ペン', 'storage/images/game_images/vocabulary/images.jpeg', 1],
            ['けしゴム', 'storage/images/game_images/vocabulary/bunbougu_keshigomu.png', 1],
            ['なふだ', 'storage/images/game_images/vocabulary/nafuda_school.png', 1],
            ['さいふ', 'storage/images/game_images/vocabulary/money_saifu_kozeni_compact.png', 1],
        ];

        // Stage4（名詞）
        $stage4Words = [
            ['トイレ', 'storage/images/game_images/vocabulary/toilet_kirei.png', 1],
            ['とけい', 'storage/images/game_images/vocabulary/mezamashidokei.png', 1],
            ['ほん', 'storage/images/game_images/vocabulary/thumbnail_book.jpg', 1],
            ['ノート', 'storage/images/game_images/vocabulary/book_note_empty.png', 1],
            ['かさ', 'storage/images/game_images/vocabulary/rain_kasa_pink.png', 1],
        ];

        // Stage5（名詞）
        $stage5Words = [
            ['バス', 'storage/images/game_images/vocabulary/square_25fb39e2-8acd-4791-9429-90134d08fb3e.png', 1],
            ['でんしゃ', 'storage/images/game_images/vocabulary/square_a16e75a2-4f15-41ef-967f-46e27d9288a5.png', 1],
            ['くるま', 'storage/images/game_images/vocabulary/142d47e5fa461dd13b8724ba3a11a876.jpg', 1],
            ['ごはん', 'storage/images/game_images/vocabulary/food_tamago_gohan4.png', 1],
            ['やさい', 'storage/images/game_images/vocabulary/vegetable.png', 1],
        ];

        // Stage6（動詞）
        $stage6Words = [
            ['みます', 'storage/images/game_images/vocabulary/smartphone_smile_boys.png', 2],
            ['ききます', 'storage/images/game_images/vocabulary/music_headphone_man.png', 2],
            ['かきます', 'storage/images/game_images/vocabulary/writing12_businesswoman.png', 2],
            ['そうじをします', 'storage/images/game_images/vocabulary/syoudoku_tenin_man.png', 2],
            ['べんきょうをします', 'storage/images/game_images/vocabulary/study_wakaru_boy.png', 2],
        ];

        // Stage7（名詞）
        $stage7Words = [
            ['サッカー', 'storage/images/game_images/vocabulary/soccer_boy_brazil_asia.png', 1],
            ['テニス', 'storage/images/game_images/vocabulary/olympic24_tennis.png', 1],
            ['ゲーム', 'storage/images/game_images/vocabulary/videogame_boy.png', 1],
            ['プール', 'storage/images/game_images/vocabulary/suiei_pool.png', 1],
            ['えいがかん', 'storage/images/game_images/vocabulary/kandou_movie_eigakan.png', 1],
        ];

        // Stage8（名詞＋形容詞）
        $stage8Words = [
            ['チョコレート', 'storage/images/game_images/vocabulary/valentinesday_itachoco2.png', 1],
            ['ながい', 'storage/images/game_images/vocabulary/dragon_wordgame_square_2.png', 3],
            ['みじかい', 'storage/images/game_images/vocabulary/dragon_wordgame_square_1.png', 3],
            ['おおい', 'storage/images/game_images/vocabulary/watercup_wordgame_square.png', 3],
            ['すくない', 'storage/images/game_images/vocabulary/square_ef6443eb-8024-478b-9650-e8d734601f86.png', 3],
        ];

        // Stage9（名詞）
        $stage9Words = [
            ['コンピュータ', 'storage/images/game_images/vocabulary/ai_computer_sousa_robot.png', 1],
            ['ひだり', 'storage/images/game_images/vocabulary/mark_arrow_left.png', 1],
            ['みぎ', 'storage/images/game_images/vocabulary/mark_arrow_right.png', 1],
            ['うえ', 'storage/images/game_images/vocabulary/mark_arrow_up.png', 1],
            ['した', 'storage/images/game_images/vocabulary/mark_arrow_down.png', 1],
        ];

        // Stage10（名詞＋形容詞）
        $stage10Words = [
            ['カメラ', 'storage/images/game_images/vocabulary/camera_lens_set.png', 1],
            ['かんたん', 'storage/images/game_images/vocabulary/ChatGPT Image 2025年12月22日 16_33_48.png', 3],
            ['むずかしい', 'storage/images/game_images/vocabulary/ufo_catcher_muzukashii.png', 3],
            ['あつい', 'storage/images/game_images/vocabulary/necchuusyou_taoreru_boy.png', 3],
            ['さむい', 'storage/images/game_images/vocabulary/fuyu_samui.png', 3],
        ];

        $allStages = [
            1 => $stage1Words,
            2 => $stage2Words,
            3 => $stage3Words,
            4 => $stage4Words,
            5 => $stage5Words,
            6 => $stage6Words,
            7 => $stage7Words,
            8 => $stage8Words,
            9 => $stage9Words,
            10 => $stage10Words,
        ];

        foreach ($allStages as $stageId => $words) {
            foreach ($words as $w) {
                DB::table('vocab_questions')->updateOrInsert(
                    [
                        'word' => $w[0],
                        'stage_id' => $stageId,
                    ],
                    [
                        'game_id' => 2,
                        'created_by_admin_id' => null,
                        'note' => null,
                        'image_url' => $w[1],
                        'part_of_speech' => $w[2],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
