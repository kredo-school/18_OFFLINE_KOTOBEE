<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;

class BadgeService
{
    public function giveNextBadge(User $user)
    {
        // ★ null でも安全にする！（後述）
        $current = $user->prefecture_id ?? 0;

        if ($current >= 47) {
            return null;
        }

        $next = $current + 1;

        $user->prefecture_id = $next;
        $user->save();

        return Badge::where('prefecture_id', $next)->first();
    }
}
