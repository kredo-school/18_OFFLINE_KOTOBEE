@extends('layouts.app')

@section('content')
    <h1>Group Dashboard</h1>

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

    {{-- 60s-count 平均 --}}
    <div style="max-width: 900px; margin: 12px 0;">
        <canvas id="{{ $kana_game_60s_avg_id }}"></canvas>
    </div>

    {{-- time_attack 平均 --}}
    <div style="max-width: 900px; margin: 12px 0;">
        <canvas id="{{ $kana_game_avg_time_attacks_id }}"></canvas>
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
    @vite(['resources/js/dashboard_graph/kana_game_60s_avg_chart.js'])
    @vite(['resources/js/dashboard_graph/kana_game_time_attacks_avg_chart.js'])

@endsection