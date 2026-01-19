<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GameResultSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $rows = [];

        /**
         * group1 の user_id を DB から取得（固定しない）
         */
        $groupId = DB::table('groups')
            ->where('name', 'group1')
            ->value('id');

        $userIds = DB::table('users')
            ->where('group_id', $groupId)
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $id = 1;

        /**
         * kana game（score）
         */
        $kanaSettings = [
            [1, 10, 40],
            [2, 10, 40],
            [3, 10, 40],
            [4, 10, 50],
            [5, 10, 30],
            [6, 10, 30],
        ];

        foreach ($kanaSettings as [$settingId, $min, $max]) {
            foreach ($userIds as $userId) {
                $rows[] = [
                    'id' => $id++,
                    'user_id' => $userId,
                    'game_id' => 1,
                    'setting_id' => $settingId,
                    'created_by_admin_id' => null,
                    'vcab_stage_id' => null,
                    'gram_stage_id' => null,
                    'score' => rand($min, $max),
                    'play_time' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        /**
         * kana game（play_time）
         */
        $kanaTimes = [
            [7, 90, 200],
            [8, 50, 150],
            [9, 120, 250],
            [10, 120, 250],
            [11, 100, 200],
            [12, 200, 350],
        ];

        foreach ($kanaTimes as [$settingId, $min, $max]) {
            foreach ($userIds as $userId) {
                $rows[] = [
                    'id' => $id++,
                    'user_id' => $userId,
                    'game_id' => 1,
                    'setting_id' => $settingId,
                    'created_by_admin_id' => null,
                    'vcab_stage_id' => null,
                    'gram_stage_id' => null,
                    'score' => null,
                    'play_time' => round(mt_rand($min * 100, $max * 100) / 100, 2),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        /**
         * vocabulary（game_id = 2）
         */
        for ($stage = 1; $stage <= 10; $stage++) {
            foreach ($userIds as $userId) {
                $rows[] = [
                    'id' => $id++,
                    'user_id' => $userId,
                    'game_id' => 2,
                    'setting_id' => null,
                    'created_by_admin_id' => null,
                    'vcab_stage_id' => $stage,
                    'gram_stage_id' => null,
                    'score' => null,
                    'play_time' => round(mt_rand(10000, 35000) / 100, 2),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        /**
         * grammar
         */
        for ($stage = 1; $stage <= 3; $stage++) {
            foreach ($userIds as $userId) {
                $rows[] = [
                    'id' => $id++,
                    'user_id' => $userId,
                    'game_id' => 3,
                    'setting_id' => null,
                    'created_by_admin_id' => null,
                    'vcab_stage_id' => null,
                    'gram_stage_id' => $stage,
                    'score' => null,
                    'play_time' => round(mt_rand(20000, 55000) / 100, 2),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('game_results')->insert($rows);
    }
}
