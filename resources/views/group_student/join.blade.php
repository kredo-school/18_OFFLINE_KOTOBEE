@extends('layouts.app')

@section('content')
<div style="max-width: 420px; margin: 0 auto; padding: 24px; background:#fbf7c7; min-height:100vh;">
    <h1 style="font-size:44px; font-weight:800; margin-bottom:18px;">Join Group</h1>

    <div style="margin-bottom: 28px;">
        <div style="font-size:32px; font-weight:700;">{{ $group->name }}</div>
        <div style="font-size:28px;">{{ $group->owner->name }}</div>
    </div>

    @if (session('status'))
        <div style="background:#fff; border:1px solid #ddd; padding:12px; border-radius:10px; margin-bottom:16px;">
            {{ session('status') }}
        </div>
    @endif

    <p style="font-size:20px; margin-bottom:12px;">
        Enter the secret word to join the group
    </p>

    <form action="{{ route('group.join.submit', $group) }}" method="POST">
        @csrf

        <input
            type="text"
            name="secret_word"
            value="{{ old('secret_word') }}"
            placeholder="Enter secret word"
            style="width:100%; padding:14px 12px; border-radius:10px; border:2px solid #ddd; font-size:20px;"
        >

        @error('secret_word')
            <div style="color:#b00020; margin-top:8px;">{{ $message }}</div>
        @enderror

        <button
            type="submit"
            style="width:100%; margin-top:22px; padding:16px 12px; border-radius:18px; border:4px solid #9a6b28;
                   background:#f2a557; font-size:34px; font-weight:800; color:#fff;"
        >
            Submit
        </button>
    </form>
</div>
@endsection
