<?php

namespace App\Http\Controllers;

use App\Models\Group;

class GroupController extends Controller
{
    /**
     * Create Group 画面
     */
    public function create()
    {
        return view('groups.create_group');
    }

    /**
     * 支払い確認中画面
     */
    public function pending()
    {
        $group = Group::where('owner_id', auth()->id())
            ->latest()
            ->first();

        if (!$group) {
            return redirect()->route('group.create');
        }

        if ($group->status === 'active') {
            return redirect()->route('group.dashboard');
        }

        return view('groups.pending', compact('group'));
    }

    public function pendingStatus()
    {
        $group = Group::where('owner_id', auth()->id())
            ->latest()
            ->first();

        if (!$group) {
            return response()->json(['status' => 'none']);
        }

        return response()->json([
            'status' => $group->status,
        ]);
    }

}
