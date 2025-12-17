<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Create Group 画面表示
     */
    public function create()
    {
        return view('groups.create_group');
    }

    /**
     * Create Group の送信処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan'   => 'required|in:basic,standard,premium',
            'name'   => 'required|string|max:12',
            'secret' => 'required|string|max:12',
            'note'   => 'nullable|string|max:255',
        ]);

        // 入力内容をセッションに保存（決済成功後に group を作成するため）
        session([
            'group_create_data' => [
                'plan'   => $request->plan,
                'name'   => $request->name,
                'secret' => $request->secret,
                'note'   => $request->note,
            ]
        ]);

        // plan → price の紐づけ
        $price = match ($request->plan) {
            'basic'    => 5.00,
            'standard' => 10.00,
            'premium'  => 20.00,
            default    => 0,
        };

        // すべての値に price を追加して PaymentController に POST で送る
        return redirect()->route('payment.create', $request->all() + ['price' => $price]);
    }
}
