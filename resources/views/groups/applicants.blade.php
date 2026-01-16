@extends('layouts.app')

{{-- admin sidebar --}}
@section('admin_sidebar')
    @include('layouts.admin_side_bar')
@endsection

@push('styles')
    @vite(['resources/css/applicants.css'])
@endpush

@section('content')

    <div class="wrap">

        <div class="card">

            <h1>List of participation applications</h1>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="alert alert-success" role="alert" style="margin-top:12px;">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert" style="margin-top:12px;">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning" role="alert" style="margin-top:12px;">
                    {{ session('warning') }}
                </div>
            @endif

            <div class="toolbar">
                <div class="field" role="search">
                    <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
                        <path fill="currentColor" d="M10 4a6 6 0 104.472 10.03l3.249 3.248a1 1 0 001.414-1.414l-3.248-3.249A6 6 0 0010 4zm0 2a4 4 0 110 8 4 4 0 010-8z"/>
                    </svg>
                    <input id="search" type="text" placeholder="Search by name..." />
                </div>

                <select id="sort" class="select">
                    <option value="asc" selected>Ascending by request date</option>
                    <option value="desc">Descending by request date</option>
                </select>

                <div class="spacer"></div>

                <button type="button" class="btn btn-green" id="bulkApprove">Approve Selected</button>
                <button type="button" class="btn btn-red" id="bulkDeny">Deny Selected</button>
            </div>

            <form id="bulkForm" method="POST">
                @csrf
                <input type="hidden" name="user_ids" id="bulkUserIds">
            </form>

            <table aria-label="participation applications">

                <thead>
                    <tr>
                        <th>
                            <input id="checkAll" class="check" type="checkbox" aria-label="Select all" />
                        </th>
                        <th>Name</th>
                        <th>request date</th>
                        <th>approval/denial</th>
                    </tr>
                </thead>

                <tbody id="tbody">
                    @foreach ($applicants as $user)
                        <tr data-name="{{ $user->name }}"
                            data-date="{{ optional($user->pivot->created_at)->toIso8601String() }}">
                            <td>
                                <input
                                    class="rowCheck check"
                                    type="checkbox"
                                    value="{{ $user->id }}"
                                    @if($user->pivot->status != 1) disabled @endif/>
                            </td>

                            <td class="name">
                                {{ $user->name }}
                            </td>

                            <td>
                                {{ optional($user->pivot->created_at)->format('Y/m/d H:i') }}
                            </td>

                            <td>
                                @if ($user->pivot->status == 1)
                                    <div class="actions">
                                        <form method="POST"
                                            action="{{ route('group.applicant.approval', ['group' => $group->id, 'user' => $user->id]) }}"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="pill approve">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('group.applicant.deny', ['group' => $group->id, 'user' => $user->id]) }}"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="pill deny">
                                                Deny
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="approved-label">Approved</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>

    <script>
        const tbody = document.getElementById("tbody");
        const search = document.getElementById("search");
        const sort = document.getElementById("sort");
        const checkAll = document.getElementById("checkAll");
        const bulkApprove = document.getElementById("bulkApprove");
        const bulkDeny = document.getElementById("bulkDeny");

        const bulkForm = document.getElementById("bulkForm");
        const bulkUserIds = document.getElementById("bulkUserIds");

        function rows() {
            return Array.from(tbody.querySelectorAll("tr"));
        }

        function applyFilter() {
            const q = search.value.trim().toLowerCase();
            rows().forEach(tr => {
                const name = (tr.dataset.name || "").toLowerCase();
                tr.style.display = name.includes(q) ? "" : "none";
            });
        }

        function applySort() {
            const dir = sort.value;
            const sorted = rows().sort((a, b) => {
                return dir === "asc"
                    ? new Date(a.dataset.date) - new Date(b.dataset.date)
                    : new Date(b.dataset.date) - new Date(a.dataset.date);
            });
            sorted.forEach(tr => tbody.appendChild(tr));
        }

        function visibleRowChecks() {
            return rows()
                .filter(tr => tr.style.display !== "none")
                .map(tr => tr.querySelector(".rowCheck"));
        }

        function syncCheckAll() {
            const checks = visibleRowChecks();
            const checked = checks.filter(c => c.checked).length;

            checkAll.checked = checks.length > 0 && checked === checks.length;
            checkAll.indeterminate = checked > 0 && checked < checks.length;
        }

        function selectedUserIds() {
            return visibleRowChecks()
                .filter(c => c.checked)
                .map(c => c.value);
        }

        /* events */
        search.addEventListener("input", () => {
            applyFilter();
            syncCheckAll();
        });

        sort.addEventListener("change", applySort);

        checkAll.addEventListener("change", () => {
            visibleRowChecks().forEach(c => c.checked = checkAll.checked);
            syncCheckAll();
        });

        tbody.addEventListener("change", e => {
            if (e.target.classList.contains("rowCheck")) {
                syncCheckAll();
            }
        });

        /* bulk approve */
        bulkApprove.addEventListener("click", () => {
            const ids = selectedUserIds();
            if (ids.length === 0) {
                alert("No users selected.");
                return;
            }

            bulkUserIds.value = ids.join(",");
            bulkForm.action = "{{ route('group.applicant.bulk.approval', ['group' => $group->id]) }}";
            bulkForm.submit();
        });

        /* bulk deny */
        bulkDeny.addEventListener("click", () => {
            const ids = selectedUserIds();
            if (ids.length === 0) {
                alert("No users selected.");
                return;
            }

            bulkUserIds.value = ids.join(",");
            bulkForm.action = "{{ route('group.applicant.bulk.deny', ['group' => $group->id]) }}";
            bulkForm.submit();
        });

        /* init */
        applySort();
        applyFilter();
        syncCheckAll();
    </script>

@endsection
