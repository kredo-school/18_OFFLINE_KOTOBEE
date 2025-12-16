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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/common.css'])

    {{-- 各ページ固有のviteファイルを読み込む --}}
    @stack('styles')

    @stack('scripts')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])



</head>

<body>
    <div id="app">

        <!-- ========================== -->
        <!--  Custom Navbar             -->
        <!-- ========================== -->
        <nav class="kb-navbar">
            <!-- Left: Logo -->
            <div class="kb-left">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('storage/images/icons/KotoBee_logo.png') }}" alt="Logo" class="kb-logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

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

                             @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        @if (Auth::check())
                                            {{ Auth::user()->name }}
                                            {{-- 変更 --}}
                                        @endif

                                        =======
                                        @if (Auth::check() && Auth::user()->avatar_url)
                                        {{-- 変更 --}}
                                            <!-- 画像の処理 -->


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
                                                @if (Auth::check() && !Auth::user()->group_id)

                                                    <a class="kb-menu-item" href="#">
                                                        Group Join
                                                        >>>>>>> master
                                                    </a>
                                                @endif

                                                <<<<<<< HEAD <div class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="navbarDropdown">
                                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                        {{ __('Logout') }}
                                                    </a>
                                                    =======
                                                    {{-- Logout --}}
                                                    <a class="kb-menu-item" href="{{ route('login') }}"
                                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                        Logout
                                                    </a>
                                                    >>>>>>> master

                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
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
        <main class="kb-main">
            @yield('content')
        </main>

    </div>

    @yield('scripts')

</body>

</html>
