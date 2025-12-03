@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Welcome, {{ $user->name }}!</h2>

    <hr>

    <h4>Vocabulary Game</h4>

    <div class="mb-3">
        <p>Select a stage to start:</p>
        @foreach($stages as $stage)
            <a href="{{ route('vocab.start', ['stage' => $stage]) }}" class="btn btn-primary mb-1">
                Stage {{ $stage }}
            </a>
        @endforeach
    </div>

    <hr>

    <h4>Recent Results</h4>
    @if($recentResults->isEmpty())
        <p>No results yet. Play a game!</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Stage</th>
                    <th>Score</th>
                    <th>Time (min)</th>
                    <th>Played At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentResults as $result)
                    <tr>
                        <td>{{ $result->game_id }}</td> {{-- ゲームIDをステージに置き換える場合は調整 --}}
                        <td>{{ $result->score }}</td>
                        <td>{{ $result->play_time }}</td>
                        <td>{{ $result->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
