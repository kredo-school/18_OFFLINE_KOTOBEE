<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentGroupController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->query('keyword'); // GETパラメータ

        $groups = Group::query()
            ->when('$keyword', function($q) use ($keyword) {
                $q->where('name', $keyword);
            })
            ->when(empty($keyword), function ($q) {
                $q->whereRaw('1 = 0');
            })
            ->orderBy('id', 'desc')
            ->get();            

        return view('group_student.search', compact('groups', 'keyword'));
    }

    public function join(Group $group)
    {
        return view('group_student.join', compact('group'));
    }
    
    public function join_submit(Request $request, Group $group) 
    {        
        $user_id = Auth::id();

        // pending = 1
        $status = 1;

        $request->validate([
            'secret_word' => ['required', 'string'],
        ]);

        // secret word チェック
        if ($request->secret_word !== $group->secret) {
            return back()
                ->withErrors(['secret_word' => 'Secret word が違います。'])
                ->withInput();
        }        

        // 既に申請/参加済みなら重複作成しない（unique対策）
        $member = GroupMember::where('group_id', $group->id)
            ->where('user_id', $user_id)
            ->first();

        if ($member) {
            // すでに pending / approved などならメッセージだけ返す
            return redirect()
                ->route('group.join', $group)
                ->with('message', 'すでに申請済み、または参加済みです。');
        }

        GroupMember::create([
            'group_id' => $group->id,
            'user_id'  => $user_id,
            'status'   => $status,
        ]);

        return redirect()
            ->route('group.join', $group)
            ->with('message', '参加申請を送信しました（承認待ち）。');
    }
}
