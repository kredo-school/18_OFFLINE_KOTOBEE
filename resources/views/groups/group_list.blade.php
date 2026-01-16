@extends('layouts.app')

{{-- admin sidebar --}}
@section('admin_sidebar')
    @include('layouts.admin_side_bar')
@endsection

@section('content')

<style>
    .group-list-page {
        padding: 64px 0;
        position: relative;
        z-index: 1; /* sidebar系の被りに負けない */
    }

    /* もし「透明な要素が上に被ってクリックを奪っている」場合に備えて、
       content側を確実に前面に出す */
    .admin-content,
    main,
    #app {
        position: relative;
        z-index: 1;
    }

    /* title */
    .group-list-title {
        font-size: 48px;
        font-weight: 700;
    }

    .group-list-subtitle {
        font-size: 20px;
        color: #555;
    }

    /* card */
    .group-list-card {
        max-width: 880px;
        margin: 0 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,.05);
        overflow: hidden;
    }

    /* summary */
    .group-summary {
        padding: 24px;
        border-bottom: 1px solid #eee;
    }
    .plan-title {
        font-size: 20px;
        font-weight: 700;
    }
    .plan-meta {
        font-size: 16px;
        color: #555;
    }

    /* list rows */
    .group-row {
        display: flex;
        justify-content: space-between;
        align-items: center;

        padding: 20px 24px;
        border-bottom: 1px solid #eee;
    }
    .group-row:last-child { border-bottom: none; }

    .group-left {
        min-width: 0;
    }
    .group-name {
        font-size: 18px;
        font-weight: 700;
    }
    .group-note {
        font-size: 16px;
        color: #666;
    }

    /* right area */
    .group-right {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-shrink: 0;
    }

    /* badge */
    .members-badge {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 999px;
        border: 1px solid #ddd;
        background: #f8f9fa;
    }

    /* make sure button doesn't "fly" */
    .group-right a {
        position: static !important;
        float: none !important;
    }

    /* button look */
    .open-btn {
        padding: 8px 18px;
        font-weight: 600;
        border-radius: 8px;
    }

    /*
      重要: サイドバー側に「画面全体を覆う透明backdrop」がある場合、
      それがクリックを奪うので、閉じている時はクリックを透過させる。
      ※このクラス名(.sidebar-backdrop)が実際の要素についている前提。
      もし違うなら、admin_side_bar側のbackdropのクラス名に合わせてください。
    */
    .sidebar-backdrop {
        pointer-events: none;
    }
    .sidebar-backdrop.is-open {
        pointer-events: auto;
    }
</style>

<div class="group-list-page">
    <div class="container">

        <div class="text-center mb-4">
            <div class="group-list-title">Your Groups</div>
            <div class="group-list-subtitle mt-2">Here are the groups you belong to:</div>
        </div>

        <div class="group-list-card">

            <div class="group-summary">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <i class="fa-solid fa-clipboard-list fa-lg text-primary"></i>
                    <div class="plan-title">
                        {{ $payment?->plan_type === 1 ? 'Basic Plan' :
                           ($payment?->plan_type === 2 ? 'Standard Plan' :
                           ($payment?->plan_type === 3 ? 'Premium Plan' : 'Custom Plan')) }}
                    </div>
                </div>

                <div class="plan-meta">
                    Current members: <strong>{{ $current_members }}</strong>
                    &nbsp; | &nbsp;
                    Maximum members: <strong>{{ $maximum_members ?? 'Unlimited' }}</strong>
                </div>
            </div>

            {{-- list --}}
            @if($groups->isEmpty())
                <div class="p-4">
                    <p class="mb-0">You don't own any groups yet.</p>
                </div>
            @else
                @foreach($groups as $group)
                    <div class="group-row">

                        <div class="group-left">
                            <div class="group-name">{{ $group->name }}</div>
                            @if($group->note)
                                <div class="group-note">{{ $group->note }}</div>
                            @endif
                        </div>

                        <div class="group-right">
                            <span class="members-badge">
                                {{ $group->approved_members_count }} members
                            </span>

                            <a class="btn btn-primary open-btn"
                               href="{{ route('group.dashboard', ['group_id' => $group->id]) }}">
                                Open
                            </a>
                        </div>

                    </div>
                @endforeach
            @endif

        </div>
    </div>
</div>

@endsection
