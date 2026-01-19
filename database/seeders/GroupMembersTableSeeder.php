<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GroupMembersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // users テーブルに存在する group_id ごとに処理
        $groupIds = DB::table('users')
            ->whereNotNull('group_id')
            ->distinct()
            ->pluck('group_id');

        foreach ($groupIds as $groupId) {

            $userIds = DB::table('users')
                ->where('group_id', $groupId)
                ->orderBy('id')
                ->pluck('id');

            foreach ($userIds as $userId) {
                DB::table('group_members')->insert([
                    'group_id'   => $groupId,
                    'user_id'    => $userId,
                    'status'     => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
