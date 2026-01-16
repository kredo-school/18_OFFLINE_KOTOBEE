<button class="open-btn" id="open_btn" aria-label="Open menu">
    <i class="fa-solid fa-bars"></i>
</button>

<div class="overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar" aria-label="Sidebar">
    <button class="close-btn" id="close_btn" aria-label="Close menu">×</button>

    <h2>Group Admin</h2>

    @php
        $current_group_id = session('current_group_id');
        $group = $current_group_id
            ? \App\Models\Group::find($current_group_id)
            : null;
    @endphp

    <nav class="menu">

        @if (isset($group))

            <a href="{{ route('group.dashboard', ['group_id' => $group->id]) }}"
                class="{{ request()->routeIs('group.dashboard') ? 'active' : '' }}">
                <i class="fa-regular fa-window-maximize"></i>
                Dashboard
            </a>

            <a href="{{ route('group.applicants', ['group_id' => $group->id]) }}"
                class="{{ request()->routeIs('group.applicants') ? 'active' : '' }}">
                <i class="fa-regular fa-user"></i>
                Members
            </a>

            <a href="{{ route('group.edit', ['group_id' => $group->id]) }}"
                class="{{ request()->routeIs('group.edit') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-column"></i>
                Edit Group
            </a>            

            <a href="{{ route('admin.vocab.create', ['group' => $group->id]) }}"
                class="{{ request()->routeIs('admin.vocab.*') || request()->routeIs('admin.grammar.*') ? 'active' : '' }}">
                <i class="fa-solid fa-circle-plus"></i> 
                Create Questions
            </a>

            <a href="{{ route('groups.students', $group->id) }}"
                class="{{ request()->routeIs('groups.students') ? 'active' : '' }}">
                <i class="fa-solid fa-trash-can"></i>
                Remove Members
            </a>

            <a href="{{ route('group.create_show') }}"
                class="{{ request()->routeIs('group.create_show') ? 'active' : '' }}">
                <i class="fa-solid fa-square-plus"></i>
                Group Create
            </a>

            <a href="{{ route('group.group_list') }}"
                class="{{ request()->routeIs('group.group_list') ? 'active' : '' }}">
                <i class="fa-solid fa-table-list"></i>
                Group list
            </a>

            <a href="{{ route('groups.delete.confirm', $group->id) }}"
                class="{{ request()->routeIs('groups.delete.confirm') ? 'active' : '' }}">
                <i class="fa-solid fa-trash"></i>
                Delete Group
            </a>

        @endif        

    </nav>
</aside>


@push('scripts')
    
    <script>

        const open_btn = document.getElementById("open_btn");
        const close_btn = document.getElementById("close_btn");
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");

        function open_sidebar() {
            sidebar.classList.add("active");
            overlay.classList.add("active");
        }

        function close_sidebar() {
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        }

        open_btn?.addEventListener("click", open_sidebar);
        close_btn?.addEventListener("click", close_sidebar);
        overlay?.addEventListener("click", close_sidebar);

        // ESCで閉じる
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") close_sidebar();
        });

        // メニューのactive切り替え
        document.querySelectorAll(".menu a").forEach(a => {
            a.addEventListener("click", () => {
                document.querySelectorAll(".menu a").forEach(x => x.classList.remove("active"));
                a.classList.add("active");
            });
        });

    </script>

@endpush
