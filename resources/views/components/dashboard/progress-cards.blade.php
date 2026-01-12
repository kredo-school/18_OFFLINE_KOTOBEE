@props([
    'cards',
    'titles' => [
        'vocabulary' => 'Vocabulary Progress',
        'grammar'    => 'Grammar Progress',
    ],
    'keys' => ['vocabulary', 'grammar'],
])


<div class="progress-section">
    
    <h2 class="dash-title">
        Progress Rankings
    </h2>
    
    <div class="playcards-wrap">
    
        <div class="playcards-grid">
    
            @foreach ($keys as $key)
    
                <div class="playcard">
    
                    <div class="playcard-header">
                        <h3 class="playcard-title">{{ $titles[$key] ?? $key }}</h3>
                        <a class="playcard-viewall" href="{{ $cards[$key]['view_all_url'] ?? '#' }}">View all</a>
                    </div>
    
                    <table class="playtable">
    
                        <thead>
    
                            <tr>
                                <th style="width:70px;">Rank</th>
                                <th>Name</th>
                                <th style="width:110px; text-align:right;">Progress</th>
                            </tr>
    
                        </thead>
    
                        <tbody>
    
                            @forelse (($cards[$key]['top5'] ?? []) as $row)
                                <tr>
                                    <td>{{ $row['rank'] }}</td>
                                    <td>{{ $row['name'] }}</td>
                                    <td style="text-align:right;">{{ $row['progress_label'] ?? '-' }}</td>
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

