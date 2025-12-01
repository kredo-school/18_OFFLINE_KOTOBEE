@extends('layouts.app')

@section('title', 'Kana Game')

    @push('styles')
        @vite(['resources/css/kana_game.css'])
    @endpush

@section('content')
    <script>
        const QUESTIONS = @json($questions);
        const MODE = @json($mode);
    </script>

    {{-- ① JSで使う設定 --}}
    <div id="kana-settings"
         data-mode="{{ $mode }}"
         data-order="{{ $order_type }}"
         data-sound="{{ $sound_type }}" {{-- 1:清音 2:濁音 3:拗音 --}}
         questions="{{ $questions }}" >
    </div>

    <!-- 出題エリア -->
    <div class="quiz-header">
        <div class="label">Score :&nbsp;</div>
        <div id="score-display">0</div>
    <div class="center-part">
        <div class="label">Choose</div>
        <div class="question" id="romaji-display"></div>
    </div>

    <div class="timer-circle">
        <svg>
        <circle class="bg" cx="25" cy="25" r="22"></circle>
        <circle class="progress" cx="25" cy="25" r="22"></circle>
        </svg>
        <div class="time-text" id="timer-display">60</div>
    </div>
    </div>

    <!-- 50音カード -->
    <div class="card-container">
        @foreach($questions as $q)
            @if($q->kana_char === '')
                <div class="card-custom empty"></div>
            @else
                <div class="card-custom kana-button"
                    data-kana="{{ $q->kana_char }}"
                    data-romaji="{{ $q->romaji }}">
                    {{ $q->kana_char }}
                </div>
            @endif
        @endforeach
    </div>

    <!-- モーダル -->
    <div id="resultModal" class="modal">
    <div class="modal-content">
        <h2>結果</h2>
        <p>スコア: <span id="result-score"></span></p>
        <p>順位: <span id="result-rank"></span></p>
        <p id="result-message" style="color: green; font-weight: bold;"></p>
        <button id="retryBtn" class="btn btn-primary mt-2">もういちど</button>
        <button id="endBtn" class="btn btn-secondary mt-2">おわり</button>
    </div>
    </div>

    @push('scripts')
        @if ($mode === "60s-count")         <!-- 60s-count -->
            @vite(['resources/js/kana_game_60s.js'])
        @elseif ($mode === "timeattack")     <!-- timeattack -->
            @vite(['resources/js/kana_game_timeattack.js'])
        @endif
    @endpush

@endsection