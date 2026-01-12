@props([
    'cards',
    'titles' => [
        'kana' => 'Kana play count',
        'vocabulary' => 'vocabulary play count',
        'grammar' => 'Grammar play count',
    ],
    'keys' => ['kana', 'vocabulary', 'grammar'],
])

<div class="playcount-section">
    
    <h2 class="dash-title">
        Game play count average and rankings
    </h2>
    
    
    <div class="playcards-wrap">
    
        <div class="playcards-grid">
    
            @foreach ($keys as $key)
    
                <div class="playcard">
    
                    {{-- カードのヘッダー --}}
                    <div class="playcard-header">
                        <h3 class="playcard-title">{{ $titles[$key] ?? $key }}</h3>
                        <a class="playcard-viewall" href="{{ $cards[$key]['view_all_url'] ?? '#' }}">View all</a>
                    </div>
    
                    {{-- カードのaverage --}}
                    <div class="playcard-avg">
                        Average plays: <strong>{{ $cards[$key]['avg'] ?? 0 }}</strong> times
                    </div>
    
    
                    <table class="playtable">
    
                        {{-- テーブルのヘッダー --}}
                        <thead>
                            
                            <tr>
                                <th style="width:70px;">Rank</th>
                                <th>Name</th>
                                <th style="width:110px; text-align:right;">Play count</th>
                            </tr>
    
                        </thead>
    
                        {{-- テーブルのボディ --}}
                        <tbody>
    
                            @forelse (($cards[$key]['top5'] ?? []) as $row)
                                <tr>
                                    <td>{{ $row['rank'] }}</td>
                                    <td>{{ $row['name'] }}</td>
                                    <td style="text-align:right;">{{ $row['play_count'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" style="color:#6b7280; padding:14px 0;">No data yet</td>
                                </tr>
                            @endforelse
    
                        </tbody>
    
                    </table>
    
                </div>
    
            @endforeach
    
        </div>
    
    </div>

</div>