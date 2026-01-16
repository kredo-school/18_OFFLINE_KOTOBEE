<nav class="kb-navbar">
    
    <!-- Left: Logo -->
    <div class="kb-left">

        @php
            $user = Auth::user();
            $isAdmin = false;

            if ($user) {
                $isAdmin = \App\Models\Group::where('owner_id', $user->id)->exists();
            }

            if (!$user) {
                $logoLink = route('login');
            } elseif ($isAdmin) {
                $logoLink = '#';
            } else {
                $logoLink = route('game.select');
            }
        @endphp

        <a href="{{ $logoLink }}">
            <img src="{{ asset('storage/images/icons/KotoBee_logo.png') }}"
                 alt="Logo" class="kb-logo">
        </a>
    </div>

    <!-- Center -->
    <div class="kb-center">
        @auth

            @php
                $current_group_id = session('current_group_id');
                $group = $current_group_id 
                    ? \App\Models\Group::find($current_group_id)
                    : null;
            @endphp
    
            @if ($group)
                <span class="kb-group-name">
                    {{ $group->name }}
                </span>
            @endif

        @endauth
    </div>

    <!-- Right -->
    <div class="kb-right">

        @auth
            
            <!-- Avatar -->
            <a href="{{ route('profile')}}" class="kb-avatar-link">
                @if (Auth::user()->avatar_url)
                    <img src="{{ asset('storage/' . Auth::user()->avatar_url) }}"
                         class="kb-avatar">
                @else
                    <i class="fa-solid fa-circle-user kb-avatar-icon"></i>
                @endif
            </a>

            <!-- Menu -->
            <div class="kb-menu">

                <details class="kb-details">

                    <summary class="kb-hamburger">☰</summary>

                    <div class="kb-menu-list">

                        @php
                            $alreadyAdmin = \App\Models\Group::where('owner_id', $user->id)->exists();
                            $alreadyPaid = \App\Models\Payment::where('owner_id', $user->id)->exists();
                        @endphp

                        @php
                            // dd(!$user->group_id, !$alreadyAdmin);
                            // dd(!$user->group_id && !$alreadyAdmin);
                        @endphp

                        @if (!$user->group_id && !$alreadyAdmin)
                            <a class="kb-menu-item" href="{{ route('group.search') }}">Group Join</a>
                        @endif

                        @if (!$user->group_id && !$alreadyAdmin && !$alreadyPaid)
                            <a class="kb-menu-item" href="{{ route('group.create') }}">
                                Create Group
                            </a>
                        @endif

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
