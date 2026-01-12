@props(['card'])

@php
    $top3 = $card['top3'] ?? collect();
    $rest = $card['rest'] ?? collect();
    $viewAllUrl = $card['view_all_url'] ?? '#';

    $suffix = function ($n) {
        if ($n === 1) return 'st';
        if ($n === 2) return 'nd';
        if ($n === 3) return 'rd';
        return 'th';
    };
@endphp

<div class="streak-card">

    <div class="streak-title">Student streak rankings</div>

    {{-- Top3 box --}}
    <div class="streak-topbox">
        @forelse ($top3 as $row)
            <div class="streak-toprow">
                <div class="streak-ord">{{ $row['rank'] }}{{ $suffix((int)$row['rank']) }}</div>
                <div class="streak-name">{{ $row['name'] }}</div>
                <div class="streak-days">{{ $row['streak'] }} days</div>
            </div>
        @empty
            <div style="padding: 10px 8px; color:#6b7280; text-align:center; font-weight:700;">
                No data yet
            </div>
        @endforelse
    </div>

    {{-- Table (4th〜) --}}
    <table class="streak-table">
        <thead>
            <tr>
                <th style="width:90px;">Rank</th>
                <th>Name</th>
                <th style="width:110px;">Streak</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($rest as $row)
                <tr>
                    <td class="streak-td-rank">{{ $row['rank'] }}</td>
                    <td class="streak-td-name">{{ $row['name'] }}</td>
                    <td class="streak-td-streak">{{ $row['streak'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="streak-empty">No data yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="streak-viewall">
        <a href="{{ $viewAllUrl }}">View all</a>
    </div>
    
</div>
