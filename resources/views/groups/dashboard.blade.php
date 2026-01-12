@extends('layouts.app')

{{-- admin sidebar --}}
@section('admin_sidebar')
    @include('layouts.admin_side_bar')
@endsection

@once

    @push('styles')        
        @vite(['resources/css/dashboard/playcount-cards.css'])
        @vite(['resources/css/dashboard/progress-cards.css'])
        @vite(['resources/css/dashboard/streak-cards.css'])
        @vite(['resources/css/dashboard/kana-charts.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/dashboard_graph/kana_game_60s_avg_chart.js'])
        @vite(['resources/js/dashboard_graph/kana_game_time_attacks_avg_chart.js'])
    @endpush

@endonce


@section('content')
    {{-- <h1>Group Dashboard</h1> --}}

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @php
        // カナゲーム共通セット
        $subtypes = ['seion', 'dakuon', 'youon'];

        ///// カナゲーム60s平均セット ////
        $kana_game_60s_avg_id = 'kana_game_60s_avg';        

        $hiragana_60s_data = [
            $avg_scores_60s['hiragana']['seion'] ?? 0,
            $avg_scores_60s['hiragana']['dakuon'] ?? 0,
            $avg_scores_60s['hiragana']['youon'] ?? 0,
        ];

        $katakana_60s_data = [
            $avg_scores_60s['katakana']['seion'] ?? 0,
            $avg_scores_60s['katakana']['dakuon'] ?? 0,
            $avg_scores_60s['katakana']['youon'] ?? 0,
        ];

        ///// カナゲームtime_attack平均セット ////
        $kana_game_avg_time_attacks_id = 'kana_game_avg_time_attacks';    

        $hiragana_time_attacks_data = [
            $avg_time_attacks['hiragana']['seion'] ?? 0,
            $avg_time_attacks['hiragana']['dakuon'] ?? 0,
            $avg_time_attacks['hiragana']['youon'] ?? 0,
        ];

        $katakana_time_attacks_data = [
            $avg_time_attacks['katakana']['seion'] ?? 0,
            $avg_time_attacks['katakana']['dakuon'] ?? 0,
            $avg_time_attacks['katakana']['youon'] ?? 0,
        ];
        
    @endphp
    
    {{-- 連続プレイ日数（1枚） --}}
    <x-dashboard.streak-cards :card="$streak_card" />

    {{-- 進捗度カード（複数） --}}
    <x-dashboard.progress-cards :cards="$progress_cards" />    

    {{-- ゲームプレイ回数 --}}
    <x-dashboard.playcount-cards :cards="$cards" />  


    <div class="dashboard-charts">

        {{-- 60s-count 平均 --}}
        <div class="chart-box">
            <canvas id="{{ $kana_game_60s_avg_id }}"></canvas>
        </div>

        {{-- time_attack 平均 --}}
        <div class="chart-box">
            <canvas id="{{ $kana_game_avg_time_attacks_id }}"></canvas>
        </div>

    </div>   

    {{-- 各jsファイルで使用する変数を宣言 --}}
    <script>
        window.kana_game_60s_avg_chart = {
            chart_id: @json($kana_game_60s_avg_id),
            labels: @json($subtypes),
            hiragana_data: @json($hiragana_60s_data),
            katakana_data: @json($katakana_60s_data),
        };

        window.kana_game_time_attacks_avg_chart = {
            chart_id: @json($kana_game_avg_time_attacks_id),
            labels: @json($subtypes),
            hiragana_data: @json($hiragana_time_attacks_data),
            katakana_data: @json($katakana_time_attacks_data),
        };

    </script>

    {{-- 各グラフ描写のjs読み込み --}}
    {{-- @vite(['resources/js/dashboard_graph/kana_game_60s_avg_chart.js'])
    @vite(['resources/js/dashboard_graph/kana_game_time_attacks_avg_chart.js'])
    @vite(['resources/css/dashboard/playcount-cards.css']) --}}

@endsection