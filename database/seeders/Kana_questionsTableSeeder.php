<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Kana_questionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kana_questions')->insert([
            // =========================	
            // ひらがな（基本50音）	
            // =========================	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'a','kana_char'=>'あ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'i','kana_char'=>'い','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'u','kana_char'=>'う','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'e','kana_char'=>'え','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'o','kana_char'=>'お','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ka','kana_char'=>'か','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ki','kana_char'=>'き','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ku','kana_char'=>'く','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ke','kana_char'=>'け','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ko','kana_char'=>'こ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'sa','kana_char'=>'さ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'shi','kana_char'=>'し','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'su','kana_char'=>'す','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'se','kana_char'=>'せ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'so','kana_char'=>'そ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ta','kana_char'=>'た','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'chi','kana_char'=>'ち','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'tsu','kana_char'=>'つ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'te','kana_char'=>'て','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'to','kana_char'=>'と','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'na','kana_char'=>'な','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ni','kana_char'=>'に','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'nu','kana_char'=>'ぬ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ne','kana_char'=>'ね','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'no','kana_char'=>'の','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ha','kana_char'=>'は','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'hi','kana_char'=>'ひ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'fu','kana_char'=>'ふ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'he','kana_char'=>'へ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ho','kana_char'=>'ほ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ma','kana_char'=>'ま','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'mi','kana_char'=>'み','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'mu','kana_char'=>'む','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'me','kana_char'=>'め','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'mo','kana_char'=>'も','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ya','kana_char'=>'や','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'yu','kana_char'=>'ゆ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'yo','kana_char'=>'よ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ra','kana_char'=>'ら','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ri','kana_char'=>'り','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ru','kana_char'=>'る','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'re','kana_char'=>'れ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'ro','kana_char'=>'ろ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'wa','kana_char'=>'わ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'wo','kana_char'=>'を','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1, 'sound_type' => 1,'romaji'=>'n','kana_char'=>'ん','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
            // =========================	
            // 濁音	
            // =========================	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'ga','kana_char'=>'が','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'gi','kana_char'=>'ぎ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'gu','kana_char'=>'ぐ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'ge','kana_char'=>'げ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'go','kana_char'=>'ご','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'za','kana_char'=>'ざ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'ji','kana_char'=>'じ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'zu','kana_char'=>'ず','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'ze','kana_char'=>'ぜ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'zo','kana_char'=>'ぞ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'da','kana_char'=>'だ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'di','kana_char'=>'ぢ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'du','kana_char'=>'づ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'de','kana_char'=>'で','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'do','kana_char'=>'ど','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'ba','kana_char'=>'ば','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'bi','kana_char'=>'び','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'bu','kana_char'=>'ぶ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'be','kana_char'=>'べ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'bo','kana_char'=>'ぼ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
            // 半濁音	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'pa','kana_char'=>'ぱ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'pi','kana_char'=>'ぴ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'pu','kana_char'=>'ぷ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'pe','kana_char'=>'ぺ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 2,'romaji'=>'po','kana_char'=>'ぽ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
            // =========================	
            // 拗音	
            // =========================	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'kya','kana_char'=>'きゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'kyu','kana_char'=>'きゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'kyo','kana_char'=>'きょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'sha','kana_char'=>'しゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'shu','kana_char'=>'しゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'sho','kana_char'=>'しょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'cha','kana_char'=>'ちゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'chu','kana_char'=>'ちゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'cho','kana_char'=>'ちょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'nya','kana_char'=>'にゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'nyu','kana_char'=>'にゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'nyo','kana_char'=>'にょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'hya','kana_char'=>'ひゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'hyu','kana_char'=>'ひゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'hyo','kana_char'=>'ひょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'mya','kana_char'=>'みゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'myu','kana_char'=>'みゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'myo','kana_char'=>'みょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'rya','kana_char'=>'りゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'ryu','kana_char'=>'りゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'ryo','kana_char'=>'りょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'gya','kana_char'=>'ぎゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'gyu','kana_char'=>'ぎゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'gyo','kana_char'=>'ぎょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'ja','kana_char'=>'じゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'ju','kana_char'=>'じゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'jo','kana_char'=>'じょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'bya','kana_char'=>'びゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'byu','kana_char'=>'びゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'byo','kana_char'=>'びょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'pya','kana_char'=>'ぴゃ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'pyu','kana_char'=>'ぴゅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>1,'sound_type' => 3,'romaji'=>'pyo','kana_char'=>'ぴょ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
            // =========================	
            // カタカナ（基本50音）	
            // =========================	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'a','kana_char'=>'ア','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'i','kana_char'=>'イ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'u','kana_char'=>'ウ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'e','kana_char'=>'エ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'o','kana_char'=>'オ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ka','kana_char'=>'カ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ki','kana_char'=>'キ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ku','kana_char'=>'ク','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ke','kana_char'=>'ケ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ko','kana_char'=>'コ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'sa','kana_char'=>'サ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'shi','kana_char'=>'シ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'su','kana_char'=>'ス','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'se','kana_char'=>'セ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'so','kana_char'=>'ソ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ta','kana_char'=>'タ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'chi','kana_char'=>'チ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'tsu','kana_char'=>'ツ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'te','kana_char'=>'テ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'to','kana_char'=>'ト','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'na','kana_char'=>'ナ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ni','kana_char'=>'ニ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'nu','kana_char'=>'ヌ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ne','kana_char'=>'ネ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'no','kana_char'=>'ノ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ha','kana_char'=>'ハ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'hi','kana_char'=>'ヒ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'fu','kana_char'=>'フ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'he','kana_char'=>'ヘ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ho','kana_char'=>'ホ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ma','kana_char'=>'マ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'mi','kana_char'=>'ミ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'mu','kana_char'=>'ム','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'me','kana_char'=>'メ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'mo','kana_char'=>'モ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ya','kana_char'=>'ヤ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'yu','kana_char'=>'ユ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'yo','kana_char'=>'ヨ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ra','kana_char'=>'ラ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ri','kana_char'=>'リ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ru','kana_char'=>'ル','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'re','kana_char'=>'レ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'ro','kana_char'=>'ロ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'wa','kana_char'=>'ワ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'wo','kana_char'=>'ヲ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 1,'romaji'=>'n','kana_char'=>'ン','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
            // =========================	
            // 濁音	
            // =========================	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'ga','kana_char'=>'ガ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'gi','kana_char'=>'ギ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'gu','kana_char'=>'グ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'ge','kana_char'=>'ゲ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'go','kana_char'=>'ゴ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'za','kana_char'=>'ザ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'ji','kana_char'=>'ジ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'zu','kana_char'=>'ズ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'ze','kana_char'=>'ゼ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'zo','kana_char'=>'ゾ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'da','kana_char'=>'ダ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'di','kana_char'=>'ヂ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'du','kana_char'=>'ヅ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'de','kana_char'=>'デ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'do','kana_char'=>'ド','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'ba','kana_char'=>'バ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'bi','kana_char'=>'ビ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'bu','kana_char'=>'ブ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'be','kana_char'=>'ベ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'bo','kana_char'=>'ボ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
            // 半濁音	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'pa','kana_char'=>'パ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'pi','kana_char'=>'ピ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'pu','kana_char'=>'プ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'pe','kana_char'=>'ペ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 2,'romaji'=>'po','kana_char'=>'ポ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
            // =========================	
            // 拗音	
            // =========================	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'kya','kana_char'=>'キャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'kyu','kana_char'=>'キュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'kyo','kana_char'=>'キョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'sha','kana_char'=>'シャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'shu','kana_char'=>'シュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'sho','kana_char'=>'ショ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'cha','kana_char'=>'チャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'chu','kana_char'=>'チュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'cho','kana_char'=>'チョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'nya','kana_char'=>'ニャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'nyu','kana_char'=>'ニュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'nyo','kana_char'=>'ニョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'hya','kana_char'=>'ヒャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'hyu','kana_char'=>'ヒュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'hyo','kana_char'=>'ヒョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'mya','kana_char'=>'ミャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'myu','kana_char'=>'ミュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'myo','kana_char'=>'ミョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'rya','kana_char'=>'リャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'ryu','kana_char'=>'リュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'ryo','kana_char'=>'リョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'gya','kana_char'=>'ギャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'gyu','kana_char'=>'ギュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'gyo','kana_char'=>'ギョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'ja','kana_char'=>'ジャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'ju','kana_char'=>'ジュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'jo','kana_char'=>'ジョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'bya','kana_char'=>'ビャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'byu','kana_char'=>'ビュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'byo','kana_char'=>'ビョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'pya','kana_char'=>'ピャ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'pyu','kana_char'=>'ピュ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
	            ['game_id'=>1, 'kana_type'=>2,'sound_type' => 3,'romaji'=>'pyo','kana_char'=>'ピョ','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
        ]);
    }
}
