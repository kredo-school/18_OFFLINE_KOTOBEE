@extends('layouts.app')

@section('title', 'Kana Game')

@push('styles')
    @vite(['resources/css/kana_game.css'])
@endpush

@section('content')
    <script>
        const QUESTIONS = @json($questions);
        const MODE = @json($mode);
        const SETTING_ID = @json($setting_id ?? null);
    </script>

    {{-- JSで使う設定 --}}
    <div id="kana-settings"
         data-mode="{{ $mode }}"
         data-order="{{ $order_type }}"
         data-sound="{{ $sound_type }}">
    </div>

    <!-- 出題エリア -->
    <div class="quiz-header">
        <div class="label">Score:&nbsp;</div>
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

    <!-- 結果モーダル -->
    <div id="result-modal" style="display:none;">
        <h2>Result</h2>

        <div id="rank-content"></div>

        <div class="modal-buttons">
            <button id="again-btn" class="kb-btn-again">Again</button>
            <a href="{{ route('game.select') }}" class="kb-btn-back">Back</a>
        </div>

        {{-- <button class="modal-close-btn" onclick="closeModal()">Close</button> --}}
    </div>
    
    <style>
        #result-modal { }
    </style>

    @push('scripts')
        @if ($mode === "60s-count")
            @vite(['resources/js/kana_game_60s.js'])
        @elseif ($mode === "timeattack")
            @vite(['resources/js/kana_game_timeattack.js'])
        @endif
    @endpush
@endsection
