<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Grammar_Question_BlocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // part_of_speech: 1=noun, 2=verb, 3=adj, 4=adv, 5=particle, 6=other
        DB::table('grammar_question_blocks')->insert([

            ['question_id'=>1, 'block_text'=>'わたし', 'part_of_speech'=>1, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>1, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>1, 'block_text'=>'せいと', 'part_of_speech'=>1, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>1, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()], 
            
            ['question_id'=>2, 'block_text'=>'わたし', 'part_of_speech'=>1, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>2, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>2, 'block_text'=>'せんせい', 'part_of_speech'=>1, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>2, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>3, 'block_text'=>'せんせい', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>3, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>3, 'block_text'=>'ともだち', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>3, 'block_text'=>'では', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>3, 'block_text'=>'ありません', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>4, 'block_text'=>'わたし', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>4, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>4, 'block_text'=>'にほんじん', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>4, 'block_text'=>'では', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>4, 'block_text'=>'ありません', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>5, 'block_text'=>'あなた', 'part_of_speech'=>1, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>5, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>5, 'block_text'=>'にほんじん', 'part_of_speech'=>1, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>5, 'block_text'=>'ですか？', 'part_of_speech'=>6, 'order_number'=>0123, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>6, 'block_text'=>'おとうと', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>6, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>6, 'block_text'=>'１３さい', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>6, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>7, 'block_text'=>'ちち', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>7, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>7, 'block_text'=>'エンジニア', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>7, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>8, 'block_text'=>'はは', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>8, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>8, 'block_text'=>'がっこう', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>8, 'block_text'=>'の', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>8, 'block_text'=>'せんせい', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>8, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>9, 'block_text'=>'わたし', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>9, 'block_text'=>'の', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>9, 'block_text'=>'かぞく', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>9, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>9, 'block_text'=>'６にん', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>9, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>10, 'block_text'=>'おねえさん', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>10, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>10, 'block_text'=>'こうこう', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>10, 'block_text'=>'３ねんせい', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>10, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>11, 'block_text'=>'これ', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>11, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>11, 'block_text'=>'ペン', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>11, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>12, 'block_text'=>'ほん', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>12, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>12, 'block_text'=>'５さつ', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>12, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>13, 'block_text'=>'これ', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>13, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>13, 'block_text'=>'わたし', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>13, 'block_text'=>'の', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>13, 'block_text'=>'けしゴム', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>13, 'block_text'=>'です', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>14, 'block_text'=>'これ', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>14, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>14, 'block_text'=>'ふでばこ', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>14, 'block_text'=>'では', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>14, 'block_text'=>'ありません', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['question_id'=>15, 'block_text'=>'これ', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>15, 'block_text'=>'は', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>15, 'block_text'=>'なふだ', 'part_of_speech'=>1, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>15, 'block_text'=>'では', 'part_of_speech'=>5, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['question_id'=>15, 'block_text'=>'ありません', 'part_of_speech'=>6, 'order_number'=>01234, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

        ]);
    }
}
