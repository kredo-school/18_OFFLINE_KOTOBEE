<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GameSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('game_settings')->insert([
            [
                'game_id' => 1,
                'mode' => '60s-count',
                'order_type' => 'regular',
                'script' => 'hiragana',
                'subtype' => 'seion',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => '60s-count',
                'order_type' => 'regular',
                'script' => 'hiragana',
                'subtype' => 'dakuon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => '60s-count',
                'order_type' => 'regular',
                'script' => 'hiragana',
                'subtype' => 'youon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => '60s-count',
                'order_type' => 'regular',
                'script' => 'katakana',
                'subtype' => 'seion',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => '60s-count',
                'order_type' => 'regular',
                'script' => 'katakana',
                'subtype' => 'dakuon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => '60s-count',
                'order_type' => 'regular',
                'script' => 'katakana',
                'subtype' => 'youon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => 'timeattack',
                'order_type' => 'regular',
                'script' => 'hiragana',
                'subtype' => 'seion',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => 'timeattack',
                'order_type' => 'regular',
                'script' => 'hiragana',
                'subtype' => 'dakuon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => 'timeattack',
                'order_type' => 'regular',
                'script' => 'hiragana',
                'subtype' => 'youon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => 'timeattack',
                'order_type' => 'regular',
                'script' => 'katakana',
                'subtype' => 'seion',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => 'timeattack',
                'order_type' => 'regular',
                'script' => 'katakana',
                'subtype' => 'dakuon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'game_id' => 1,
                'mode' => 'timeattack',
                'order_type' => 'regular',
                'script' => 'katakana',
                'subtype' => 'youon',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
