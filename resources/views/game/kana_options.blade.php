@extends('layouts.app')


@push('styles')
    {{-- ゲーム用のcss --}}
    @vite(['resources/css/kana_game.css'])
    {{-- ゲーム開始時のmodal --}}
    @vite(['resources/css/game_start_modal.css'])
@endpush

@push('scripts')
    {{-- ゲーム開始時のmodal --}}
    @vite(['resources/js/game_start_modal.js'])
@endpush

@section('content')
<div class="container">

    <h2 class="kana-options-title">Kana Game Options</h2>

    {{-- 説明文 --}}
    <div class="kana-options-description">
        <p class="desc-main">ゲームをえらんでね！</p>
        <p class="desc-main">ひらがな、カタカナをれんしゅうします。</p>
        <p class="desc-sub">60s-count：何個（なんこ）わかるかな？</p>
        <p class="desc-sub">time-attack：何秒（なんびょう）で できるかな？</p>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mode</th>
                <th>Script</th>
                <th>Subtype</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($settings as $setting)
                @php
                    $isPlayed = in_array($setting->id, $playedSettingIds);
                @endphp

                <tr class="{{ $isPlayed ? 'option-played' : 'option-not-played' }}">
                    <td>{{ $setting->id }}</td>
                    <td>{{ $setting->mode }}</td>
                    <td>{{ $setting->script }}</td>
                    <td>{{ $setting->subtype }}</td>
                    <td>
                        <a href="{{ route('kana.start_page', $setting->id) }}"
                        class="btn btn-primary btn-sm js-open-start-modal">
                            Start
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>

{{-- モーダル差し込み --}}
<div id="start-modal-root"></div>

@endsection
