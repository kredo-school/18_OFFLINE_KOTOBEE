<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    {{-- font-awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- bootstrap cdn -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

    <!-- Styles -->
    @vite(['resources/css/common.css'])

    {{-- 各ページ固有のviteファイルを読み込む --}}
    @stack('styles')
    @stack('scripts')
</head>

<body>

    <!-- ========================== -->
    <!--  Custom Navbar             -->
    <!-- ========================== -->
    <nav class="kb-navbar">
        <!-- Left: Logo -->
        <div class="kb-left">
            <a href="{{ url('/') }}">
                <img src="{{ asset('storage/images/icons/KotoBee_logo.png') }}" 
                        alt="Logo" class="kb-logo">
            </a>
        </div>

        <!-- Center: Group Name (if user belongs to group) -->
        <div class="kb-center">
            @auth
                @if (Auth::user()->group_id)
                    <span class="kb-group-name">
                        {{ Auth::user()->group->name ?? '' }}
                    </span>
                @endif
            @endauth
        </div>

        <!-- Right: Avatar + Hamburger Menu -->
        <div class="kb-right">

            @auth
            <!-- Avatar -->
            <a href="#" class="kb-avatar-link">

                @if (Auth::user()->avatar_url)
                    <!-- アバター画像があるとき -->
                    <img src="{{ asset('storage/images/avatars/' . Auth::user()->avatar_url) }}"
                        class="kb-avatar">
                @else
                    <!-- アバター画像がないとき：Font Awesome アイコン -->
                    <i class="fa-solid fa-circle-user kb-avatar-icon"></i>
                @endif
            </a>

            <!-- Hamburger Menu -->
            <div class="kb-menu">
                <details class="kb-details">
                    <!-- Triangle removed by CSS -->
                    <summary class="kb-hamburger">☰</summary>

                    <div class="kb-menu-list">

                        {{-- Group Join (only when not in a group) --}}
                        @if (!Auth::user()->group_id)
                            <a class="kb-menu-item" href="#">
                                Group Join
                            </a>
                        @endif

                        {{-- Logout --}}
                        <a class="kb-menu-item"
                        href="{{ route('login') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form"
                            action="{{ route('logout') }}"
                            method="POST"
                            class="d-none">
                            @csrf
                        </form>

                    </div>
                </details>
            </div>
            @endauth

        </div>
    </nav>
    <!-- ========================== -->

    <!-- Body Content -->
    @yield('content')

    @yield('scripts')

</body>
</html>