<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class Grammar_QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grammar_questions')->insert([

            ['id' => 1, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>1, 'note'=>'stage_1', 'image_url'=>'/storage/images/game_images/grammar/stage_1/correct_images/q_1.png', 'correct_sentence'=>'わたし は せいと です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],   
            ['id' => 2, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>1, 'note'=>'stage_1', 'image_url'=>'/storage/images/game_images/grammar/stage_1/correct_images/q_2.png', 'correct_sentence'=>'わたし は せんせい です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 3, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>1, 'note'=>'stage_1', 'image_url'=>'/storage/images/game_images/grammar/stage_1/correct_images/q_3.png', 'correct_sentence'=>'せんせい は ともだち では ありません', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 4, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>1, 'note'=>'stage_1', 'image_url'=>'/storage/images/game_images/grammar/stage_1/correct_images/q_4.png', 'correct_sentence'=>'わたし は にほんじん では ありません', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 5, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>1, 'note'=>'stage_1', 'image_url'=>'/storage/images/game_images/grammar/stage_1/correct_images/q_5.png', 'correct_sentence'=>'あなた は にほんじん ですか', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],



            ['id' => 6, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>2, 'note'=>'stage_2', 'image_url'=>'/storage/images/game_images/grammar/stage_2/correct_images/q_1.png', 'correct_sentence'=>'おとうと は １３さい です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],   
            ['id' => 7, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>2, 'note'=>'stage_2', 'image_url'=>'/storage/images/game_images/grammar/stage_2/correct_images/q_2.png', 'correct_sentence'=>'ちち は エンジニア です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 8, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>2, 'note'=>'stage_2', 'image_url'=>'/storage/images/game_images/grammar/stage_2/correct_images/q_3.png', 'correct_sentence'=>'はは は がっこう の せんせい です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 9, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>2, 'note'=>'stage_2', 'image_url'=>'/storage/images/game_images/grammar/stage_2/correct_images/q_4.png', 'correct_sentence'=>'わたし の かぞく は ６にん です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 10, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>2, 'note'=>'stage_2', 'image_url'=>'/storage/images/game_images/grammar/stage_2/correct_images/q_5.png', 'correct_sentence'=>'おねえさん は こうこう ３ねんせい です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            
            

            ['id' => 11, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>3, 'note'=>'stage_3', 'image_url'=>'/storage/images/game_images/grammar/stage_3/correct_images/q_1.png', 'correct_sentence'=>'これ は ペン です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],   
            ['id' => 12, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>3, 'note'=>'stage_3', 'image_url'=>'/storage/images/game_images/grammar/stage_3/correct_images/q_2.png', 'correct_sentence'=>'ほん は ５さつ です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 13, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>3, 'note'=>'stage_3', 'image_url'=>'/storage/images/game_images/grammar/stage_3/correct_images/q_3.png', 'correct_sentence'=>'これ は わたし の けしゴム です', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 14, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>3, 'note'=>'stage_3', 'image_url'=>'/storage/images/game_images/grammar/stage_3/correct_images/q_4.png', 'correct_sentence'=>'これ は ふでばこ では ありません', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['id' => 15, 'game_id'=>3, 'created_by_admin_id'=>1, 'stage_id'=>3, 'note'=>'stage_3', 'image_url'=>'/storage/images/game_images/grammar/stage_3/correct_images/q_5.png', 'correct_sentence'=>'これ は なふだ では ありません', 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

        ]);
    }
}
