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

    <h2 class="mb-4">Kana Game Options</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mode</th>
                {{-- <th>Order</th> --}}
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
        {{-- <tbody>
            @foreach($settings as $setting)
            <tr>
                <td>{{ $setting->id }}</td>
                <td>{{ $setting->mode }}</td>
                {{-- <td>{{ $setting->order_type }}</td> --}}
                {{-- <td>{{ $setting->script }}</td>
                <td>{{ $setting->subtype }}</td>
                <td> --}}
                    {{-- <a href="{{ route('kana.start', $setting->id) }}" 
                       class="btn btn-primary btn-sm">
                        Start
                    </a> --}}

                    {{-- <a href="{{ route('kana.start_page', $setting->id) }}" 
                        class="btn btn-primary btn-sm js-open-start-modal">
                        Start
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody> --}}
    </table>

</div>

{{-- モーダル差し込み --}}
<div id="start-modal-root"></div>

@endsection
