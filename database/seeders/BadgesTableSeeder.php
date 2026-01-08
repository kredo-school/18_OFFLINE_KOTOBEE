<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BadgesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('badges')->insert([
            ['prefecture_id' =>  1, 'name_hiragana' => 'ほっかいどう', 'file_name' => '01_Hokkaido.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' =>  2, 'name_hiragana' => 'あおもり', 'file_name' => '02_Aomori.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' =>  3, 'name_hiragana' => 'いわて', 'file_name' => '03_Iwate.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' =>  4, 'name_hiragana' => 'みやぎ', 'file_name' => '04_Miyagi.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' =>  5, 'name_hiragana' => 'あきた', 'file_name' => '05_Akita.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' =>  6, 'name_hiragana' => 'やまがた', 'file_name' => '06_Yamagata.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' =>  7, 'name_hiragana' => 'ふくしま', 'file_name' => '07_Fukushima.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' =>  8, 'name_hiragana' => 'いばらき', 'file_name' => '08_Ibaraki.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' =>  9, 'name_hiragana' => 'とちぎ', 'file_name' => '09_Tochigi.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 10, 'name_hiragana' => 'ぐんま', 'file_name' => '10_Gunma.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 11, 'name_hiragana' => 'さいたま', 'file_name' => '11_Saitama.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 12, 'name_hiragana' => 'ちば', 'file_name' => '12_Chiba.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 13, 'name_hiragana' => 'とうきょう', 'file_name' => '13_Tokyo.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 14, 'name_hiragana' => 'かながわ', 'file_name' => '14_Kanagawa.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 15, 'name_hiragana' => 'にいがた', 'file_name' => '15_Niigata.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 16, 'name_hiragana' => 'とやま', 'file_name' => '16_Toyama.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 17, 'name_hiragana' => 'いしかわ', 'file_name' => '17_Ishikawa.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 18, 'name_hiragana' => 'ふくい', 'file_name' => '18_Fukui.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 19, 'name_hiragana' => 'やまなし', 'file_name' => '19_Yamanashi.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 20, 'name_hiragana' => 'ながの', 'file_name' => '20_Nagano.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 21, 'name_hiragana' => 'ぎふ', 'file_name' => '21_Gifu.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 22, 'name_hiragana' => 'しずおか', 'file_name' => '22_Shizuoka.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 23, 'name_hiragana' => 'あいち', 'file_name' => '23_Aichi.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 24, 'name_hiragana' => 'みえ', 'file_name' => '24_Mie.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 25, 'name_hiragana' => 'しが', 'file_name' => '25_Shiga.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 26, 'name_hiragana' => 'きょうと', 'file_name' => '26_Kyoto.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 27, 'name_hiragana' => 'おおさか', 'file_name' => '27_Osaka.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 28, 'name_hiragana' => 'ひょうご', 'file_name' => '28_Hyogo.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 29, 'name_hiragana' => 'なら', 'file_name' => '29_Nara.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 30, 'name_hiragana' => 'わかやま', 'file_name' => '30_Wakayama.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 31, 'name_hiragana' => 'とっとり', 'file_name' => '31_Tottori.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 32, 'name_hiragana' => 'しまね', 'file_name' => '32_Shimane.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 33, 'name_hiragana' => 'おかやま', 'file_name' => '33_Okayama.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 34, 'name_hiragana' => 'ひろしま', 'file_name' => '34_Hiroshima.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 35, 'name_hiragana' => 'やまぐち', 'file_name' => '35_Yamaguchi.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 36, 'name_hiragana' => 'とくしま', 'file_name' => '36_Tokushima.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 37, 'name_hiragana' => 'かがわ', 'file_name' => '37_Kagawa.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 38, 'name_hiragana' => 'えひめ', 'file_name' => '38_Ehime.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 39, 'name_hiragana' => 'こうち', 'file_name' => '39_Kochi.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 40, 'name_hiragana' => 'ふくおか', 'file_name' => '40_Fukuoka.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 41, 'name_hiragana' => 'さが', 'file_name' => '41_Saga.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 42, 'name_hiragana' => 'ながさき', 'file_name' => '42_Nagasaki.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 43, 'name_hiragana' => 'くまもと', 'file_name' => '43_Kumamoto.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 44, 'name_hiragana' => 'おおいた', 'file_name' => '44_Oita.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 45, 'name_hiragana' => 'みやざき', 'file_name' => '45_Miyazaki.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 46, 'name_hiragana' => 'かごしま', 'file_name' => '46_Kagoshima.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
            ['prefecture_id' => 47, 'name_hiragana' => 'おきなわ', 'file_name' => '47_Okinawa.svg','created_at' => Carbon::now(),'updated_at' => Carbon::now()],
        ]);
    }
}