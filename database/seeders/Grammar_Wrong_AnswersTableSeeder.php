<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Grammar_Wrong_AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // part_of_speech: 1=noun, 2=verb, 3=adj, 4=adv, 5=particle, 6=other
        // サンプルでそれぞれの問題に一つの間違いの答えを付与
        DB::table('grammar_wrong_answers')->insert([            

            // 例
            ['question_id'=>1, 'wrong_order'=>'2,1,0,3', 'wrong_sentence'=>'せいと は わたし です', 'wrong_image_url'=>'/storage/images/game_images/grammar/stage_1/wrong_images/question_1/1.png', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>2, 'wrong_order'=>'2,1,0,3', 'wrong_sentence'=>'せんせい は わたし です', 'wrong_image_url'=>'/storage/images/game_images/grammar/stage_1/wrong_images/question_2/1.png', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>3, 'wrong_order'=>'2,1,0,3,4', 'wrong_sentence'=>'ともだち は せんせい では ありません', 'wrong_image_url'=>'/storage/images/game_images/grammar/stage_1/wrong_images/question_3/1.png', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            
            ['question_id'=>4, 'wrong_order'=>'2,1,0,3,4', 'wrong_sentence'=>'にほんじん は わたし では ありません', 'wrong_image_url'=>'/storage/images/game_images/grammar/stage_1/wrong_images/question_4/1.png', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>5, 'wrong_order'=>'2,1,0,3', 'wrong_sentence'=>'にほんじん は あなた ですか？', 'wrong_image_url'=>'/storage/images/game_images/grammar/stage_1/wrong_images/question_5/1.png', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

        ]);
    }
}
