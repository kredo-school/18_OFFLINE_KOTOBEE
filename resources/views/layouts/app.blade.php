<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/common.css'])
    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('scripts')
</head>

<body>
    <div id="app">

        <!-- ========================== -->
        <!-- Custom Navbar -->
        <!-- ========================== -->
        <nav class="kb-navbar">

            <!-- Left -->
            <div class="kb-left">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('storage/images/icons/KotoBee_logo.png') }}" class="kb-logo">
                </a>
            </div>

            <!-- Center -->
            <div class="kb-center">
                @auth
                    @if (Auth::user()->group_id)
                        <span class="kb-group-name">
                            {{ Auth::user()->group->name }}
                        </span>
                    @endif
                @endauth
            </div>

            <!-- Right -->
            <div class="kb-right">
                @auth
                    <!-- Avatar -->
                    <a href="{{ route('profile') }}" class="kb-avatar-link">
                        @if (Auth::user()->avatar_url)
                            <img src="{{ asset('storage/' . Auth::user()->avatar_url) }}" class="kb-avatar">
                        @else
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
                                    </a>
                                @endif
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    {{-- Logout --}}
                                    <a class="kb-menu-item" href="{{ route('login') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                        </details>
                    </div>
                @endauth
            </div>

        </nav>
        <!-- ========================== -->

        <!-- Body -->
        <main class="kb-main">
            @yield('content')
        </main>

    </div>
</body>

</html>
