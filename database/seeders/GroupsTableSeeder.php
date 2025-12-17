<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


///// 仮のグループをseederで作成 /////
class GroupsTableSeeder extends Seeder
{   
    public function run(): void
    {
        $owner = User::first();

        $groups = [];

        foreach (range(1, 5) as $i) {
            $groups[] = [
                'name'       => 'Group' . $i,
                'note'       => "Tokyo High School - Grade {$i}",
                'owner_id'   => $owner->id,
                'secret'     => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('groups')->insert($groups);
        
    }
}
