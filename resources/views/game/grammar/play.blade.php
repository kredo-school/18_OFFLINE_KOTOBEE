@extends('layouts.grammar_game')

@section('content')

    {{-- 結果モーダル --}}
    <div id="result-modal" style="display:none;">

        <h2>Result</h2>

        <div id="rank-content"></div>

        <div class="modal-buttons">
            <button id="again-btn" class="kb-btn-again">Again</button>
            <a href="{{ route('game.select') }}" class="kb-btn-back">Back</a>
        </div>

    </div>


    {{-- Phaserを表示する場所 --}}
    <div id="phaser-root"></div>


@endsection


@section('scripts')

    {{-- Phaser 読み込み --}}
    <script src="https://cdn.jsdelivr.net/npm/phaser@3.80.0/dist/phaser.js"></script>

    {{-- あなたのゲーム用 JS --}}
    @vite('resources/js/grammar_game.js')

    <script>
        // ページ表示後に JSON API を叩いて、JS に渡す
        fetch('/api/grammar/start/{{ $id }}')
            .then(res => {
                if (!res.ok) {
                    // ステータスコードをコンソールに出す
                    throw new Error('HTTP error ' + res.status);
                }
                return res.json();
            })
            .then(data => {
                // console.log('questions from API:', data);
                // ここでグローバル関数に渡す
                window.startGrammarGame(data.data, data.stage_id);
            })
            .catch(err => console.error(err));
    </script>

@endsection
