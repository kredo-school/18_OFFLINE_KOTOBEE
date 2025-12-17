@extends('layouts.app')

@section('content')
    <div style="max-width: 420px; margin: 0 auto; padding: 24px; background:#fbf7c7; min-height:100vh;">
        <h3 style="font-weight: 800; font-size: 32px; margin-bottom: 16px;">Group_Search</h3>

        {{-- 検索フォーム（GET） --}}
        <form action="{{ route('group.search') }}" method="GET" style="margin-bottom: 24px;">
            <div style="display:flex; gap:8px;">
                <input
                    type="text"
                    name="keyword"
                    value="{{ $keyword ?? '' }}"
                    placeholder="Search"
                    style="flex:1; padding: 14px 12px; border-radius: 10px; border: 2px solid #ddd;"
                />
                <button
                    type="submit"
                    style="padding: 14px 14px; border-radius: 10px; border: 2px solid #ddd; background: #fff; font-weight:700;"
                >
                    検索
                </button>
            </div>
        </form>

        {{-- 検索結果 --}}
        @forelse ($groups as $group)
            <a
                href="{{ route('group.join', $group) }}"
                style="display:block; text-decoration:none; color:inherit; margin-bottom:16px;"
            >
                <div style="background:#fff; border:2px solid #e5e5e5; border-radius:12px; padding:18px;">
                    <div style="font-size: 28px; font-weight: 700; margin-bottom: 8px;">
                        {{ $group->name }}
                    </div>
                    <div style="font-size: 18px; color:#444;">
                        {{ $group->note }}
                    </div>

                    {{-- ボタンっぽく見せたい場合（押すと遷移はaタグ） --}}
                    <div style="margin-top: 12px;">
                        <span style="display:inline-block; padding:10px 12px; border-radius:10px; border:1px solid #ddd; background:#f8f8f8; font-weight:700;">
                            このグループを見る →
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <p style="color:#666;">該当するグループがありません。</p>
        @endforelse

    </div>
@endsection