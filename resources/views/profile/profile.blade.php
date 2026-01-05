{{-- Profile Page Blade --}}
@extends('layouts.app')

@push('styles')
    @vite(['resources/css/profile.css'])
@endpush

@push('scripts')
<script src="{{ asset('js/streak.js') }}"></script>
@endpush

@section('content')



    {{-- Top Bar --}}
    <div class="top-bar">
        <a href="{{ route('game.select') }}">
        <i class="fa-solid fa-arrow-left back-icon black"></i>
        </a>
        <span class="top-title">Profile</span>
        <a href="{{ route('profile.edit') }}">
    <i class="fa-solid fa-gear setting-icon black"></i>
</a>
    </div>

    {{-- Profile Card --}}
  <div class="profile-wrapper">
    <div class="profile-container">
        <div class="avatar-wrapper">
            <div class="profile-avatar">
                @if($user->avatar_url)
                    <img src="{{ asset('storage/'.$user->avatar_url) }}" alt="Avatar" class="avatar-img">
                @else
                    <i class="fa-solid fa-circle-user default-avatar"></i>
                @endif
            </div>
        </div>

        <h2 class="username">{{ $user->name }}</h2>

        <div class="streak-box">
            <p class="streak-label">Streak 🔥</p>
            <p class="streak-number" id="streakCounter" data-streak="{{ $streak }}">
    {{ $streak ?? 0 }}
</p>

        </div>

        <p class="view-stats">view stats</p>

        <div class="menu-buttons">
            <a href="{{ route('vocabulary.index') }}" class="menu-btn vocab">
                <i class="fa-solid fa-book"></i>
                <span>Vocabulary</span>
            </a>

            <a href="{{ route('grammar.index') }}" class="menu-btn grammar">
                <i class="fa-solid fa-pen"></i>
                <span>Grammar</span>
            </a>
        </div>
    </div>
</div>

{{-- ===== Badges Japan Map Area 2026/1/1 (add:Tadashi)===== --}}
<div class="badge-map-wrapper">
    <h3 class="badge-title">獲得バッジ - Japan Map</h3>

    <div class="japan-map-container">

        {{-- 白地図 --}}
        <img src="{{ asset('storage/images/badges/white_map.svg') }}" class="japan-base-map">

        {{-- 取得県だけ重ねる --}}
        @foreach($badges as $badge)
            <img src="{{ asset('storage/images/badges/'.$badge->file_name) }}"
                 class="badge-prefecture badge-{{ $badge->id }}">
        @endforeach

    </div>
</div>

@endsection
