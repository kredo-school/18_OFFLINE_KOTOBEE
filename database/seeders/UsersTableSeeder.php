<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $users = [];

        for ($i = 11; $i <= 50; $i++) {
            $users[] = [
                'name'            => 'Student' . $i,
                'email'           => 'student' . $i . '@gmail.com',
                'password'        => '$2y$12$/cp3OeAXFr5ELnQvzi.1Tu6d2xrYKlXMU8FawMdme.e7Tu8j/Zt.m',
                'avatar_url'      => null,
                'role'            => 1,
                'group_id'        => $i <= 30 ? 5 : 6,
                'prefecture_id'   => null,
                'acquired_at'     => null,
                'remember_token'  => null,
                'streak'          => 0,
                'last_played_at'  => null,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }

        DB::table('users')->insert($users);
    }
}

