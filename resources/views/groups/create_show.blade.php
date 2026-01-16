@extends('layouts.app')

{{-- admin sidebar --}}
@section('admin_sidebar')
    @include('layouts.admin_side_bar')
@endsection

@section('content')

<style>
    .cg-page {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 48px 16px;
    }

    .cg-wrap {
        width: 100%;
        max-width: 720px;
        text-align: center;
    }

    .cg-title {
        font-size: 64px;
        font-weight: 800;
        margin: 0 0 8px;
        letter-spacing: 0.5px;
        color: #111;
    }

    .cg-subtitle {
        font-size: 32px;
        font-weight: 500;
        margin: 0 0 28px;
        color: #222;
        opacity: 0.9;
    }

    .cg-card {
        background: #fff;
        border-radius: 10px;
        padding: 28px 26px;
        box-shadow: 0 2px 0 rgba(0,0,0,0.08);
        border: 1px solid rgba(0,0,0,0.08);
        text-align: left;
    }

    .cg-label {
        display: block;
        font-weight: 700;
        font-size: 18px;
        margin: 10px 0 8px;
        color: #111;
    }

    .cg-input, .cg-textarea {
        width: 100%;
        border: 2px solid #e5e5e5;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 18px;
        outline: none;
        background: #fff;
    }

    .cg-input::placeholder, .cg-textarea::placeholder {
        color: #b3b3b3;
    }

    .cg-input:focus, .cg-textarea:focus {
        border-color: #cfcfcf;
    }

    .cg-help {
        margin-top: 8px;
        font-size: 12px;
        color: #444;
        opacity: 0.9;
    }

    .cg-textarea {
        min-height: 240px;
        resize: vertical;
        line-height: 1.5;
    }

    .cg-actions {
        margin-top: 26px;
    }

    .cg-btn {
        width: 100%;
        border: none;
        border-radius: 6px;
        padding: 14px 16px;
        font-size: 22px;
        font-weight: 700;
        cursor: pointer;
        background: #3f63ff;
        color: #fff;
    }

    .cg-btn:hover {
        filter: brightness(0.98);
    }

    .cg-errors {
        text-align: left;
        margin: 0 0 14px;
        padding: 12px 14px;
        background: #fff3f3;
        border: 1px solid #ffd2d2;
        border-radius: 8px;
        color: #b30000;
        font-size: 14px;
    }
</style>

    <div class="cg-page">
        <div class="cg-wrap">

            <h1 class="cg-title">Create Group</h1>

            <p class="cg-subtitle">Create your new group</p>

            <div class="cg-card">

                {{-- バリデーションエラー --}}
                @if ($errors->any())
                    <div class="cg-errors">
                        <ul style="margin:0; padding-left:18px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- フォーム --}}
                <form method="POST" action="{{ route('group.new_store') }}">
                    @csrf

                    <label class="cg-label" for="name">Group Name</label>
                    <input
                        id="name"
                        name="name"
                        class="cg-input"
                        type="text"
                        maxlength="12"
                        placeholder="e.g. Grade 7"
                        value="{{ old('name') }}"
                        required
                    />

                    <label class="cg-label" for="secret">Secret Word</label>                
                    <input
                        id="secret"
                        name="secret"
                        class="cg-input"
                        type="text"
                        maxlength="12"
                        placeholder="Student use this word to join"
                        value="{{ old('secret') }}"
                        required
                    />

                    <div class="cg-help">
                        Share this word with your students. They'll need it when joining the group
                    </div>

                    <label class="cg-label" for="note" style="margin-top:18px;">Group Memo</label>
                    <textarea
                        id="note"
                        name="note"
                        class="cg-textarea"
                        maxlength="255"
                        placeholder="This group is for Grade 7 English learners. Weekly homework will be assigned every Monday. Please check messages regularly."
                    >{{ old('note') }}</textarea>

                    <div class="cg-actions">
                        <button class="cg-btn" type="submit">Create</button>
                    </div>

                </form>

            </div>

        </div>
    </div>
@endsection
