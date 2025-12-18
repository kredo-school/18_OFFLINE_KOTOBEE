<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;



class ProfileController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $streak = $user->streak;  // ←DBカラム名に合わせる

        return view('profile.profile', compact('user', 'streak'));
    }


    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user()
        ]);
    }


    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|max:2048', // 2MBまで
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        // avatar処理
        if ($request->hasFile('avatar')) {
            // 古い画像を削除
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_url = $path;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::logout();       // ログアウト
        $user->delete();      // アカウント削除

        return redirect('/')->with('status', 'アカウントを削除しました。');
    }
}
