@extends('layouts.app')

@once
    @push('styles')

        @vite(['resources/css/dashboard/back-btn.css'])

        <style>
            .pc-wrap {
                min-height: calc(100vh - 140px);
                display: flex;
                justify-content: center;
                align-items: flex-start;
                padding: 40px 16px;
            }
            .pc-card {
                width: 900px;
                max-width: 100%;
                background: #fff;
                border: 5px solid #e5e7eb;
                border-radius: 10px;
                box-shadow: 0 8px 18px rgba(0,0,0,0.18);
                overflow: hidden;
            }
            .pc-head {
                padding: 22px 26px 10px;
            }
            .pc-title {
                font-size: 32px;
                font-weight: 700;
                margin: 0 0 10px;
                color: #111827;
            }
            .pc-divider {
                height: 2px;
                background: #e5e7eb;
            }
            .pc-controls {
                display: flex;
                align-items: center;
                gap: 26px;
                padding: 18px 26px;
            }
            .pc-muted { color: #9ca3af; }
            .pc-avg {
                font-size: 20px;
                color: #111827;
            }
            .pc-table {
                width: 100%;
                border-collapse: collapse;
            }
            .pc-table thead th {
                background: #e5e7eb;
                padding: 10px 12px;
                font-size: 20px;
                text-align: center;
                color: #111827;
            }
            .pc-table tbody td {
                padding: 18px 12px;
                font-size: 22px;
                border-bottom: 2px solid #e5e7eb;
                text-align: center;
            }
            .pc-table tbody td.name {
                text-align: center;
                font-weight: 600;
            }
            .pc-table tbody td.count {
                text-align: right;
                padding-right: 24px;
                font-weight: 700;
            }
            .pc-ranklink {
                color: inherit;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .pc-ranklink:hover { text-decoration: underline; }
            .pc-arrow { font-size: 16px; opacity: 0.85; }
        </style>
    @endpush
@endonce

@section('content')

{{-- 戻るボタン（dashboardへ） --}}
<x-dashboard.back-to-dashboard :group="$group" />

<div class="pc-wrap">
    <div class="pc-card">

        <div class="pc-head">
            <h2 class="pc-title">Streak rankings - View all</h2>
        </div>

        <div class="pc-divider"></div>

        {{-- Controls row（カテゴリなし） --}}
        <div class="pc-controls">

            <div class="pc-muted">All games</div>

            <div class="pc-avg">
                Average streak: {{ $avg_streak }} days
            </div>

        </div>

        @php
            $nextOrder = ($order === 'desc') ? 'asc' : 'desc';
            $arrow = ($order === 'desc') ? '▼' : '▲';
        @endphp

        <table class="pc-table">

            <thead>
                <tr>
                    <th style="width: 160px;">
                        <a class="pc-ranklink"
                           href="{{ route('group.streak', ['group_id' => $group->id]) . '?order=' . $nextOrder }}">
                            Rank <span class="pc-arrow">{{ $arrow }}</span>
                        </a>
                    </th>
                    <th>Name</th>
                    <th style="width: 220px;">Streak</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($ranking as $row)
                    <tr>
                        <td>
                            @if ($order === 'asc')
                                {{ count($ranking) - $loop->index }}
                            @else
                                {{ $loop->iteration }}
                            @endif
                        </td>

                        <td class="name">{{ $row['name'] }}</td>

                        <td class="count">{{ $row['streak'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 18px; color:#6b7280;">No data yet</td>
                    </tr>
                @endforelse
            </tbody>
            
        </table>

    </div>
</div>

@endsection
