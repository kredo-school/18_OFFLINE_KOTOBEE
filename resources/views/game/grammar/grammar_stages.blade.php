@extends('layouts.game_stages')
{{-- @extends('layouts.app')  --}}

{{-- 固有script呼び出し --}}
@push('scripts')
    @vite(['resources/js/game_stages.js'])
    {{-- ゲーム開始時のmodal --}}
    @vite(['resources/js/game_start_modal.js'])
    
    {{-- 画面共通フォント --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
@endpush

{{-- 固有css呼び出し --}}
@push('styles')
    @vite(['resources/css/common.css'])

    @vite(['resources/css/game_stages.css'])
    {{-- ゲーム開始時のmodal --}}
    @vite(['resources/css/game_start_modal.css'])
@endpush

{{-- 内容 --}}
@section('content')

{{-- 移動ボタン --}}
<div class="controls">
    <button id="prev" class="arrow-btn">
        <svg viewBox="0 0 30 30" class="arrow-icon" aria-hidden="true">
            <path d="M14.8438 29.6878L1.12057e-05 14.844L14.8438 0.000266552L18.5085 3.63663L9.9716 12.1736H29.233V17.5145H9.9716L18.5085 26.0372L14.8438 29.6878Z"/>
        </svg>            
    </button>
    <span id="info">Stage: 1</span>
    <button id="next" class="arrow-btn">
        <svg viewBox="0 0 30 30" class="arrow-icon" aria-hidden="true">
            <path d="M14.3891 -0.000266403L29.2329 14.8435L14.3891 29.6872L10.7244 26.0509L19.2613 17.5139L-5.72205e-05 17.5139V12.173H19.2613L10.7244 3.6503L14.3891 -0.000266403Z"/>
        </svg>
    </button>
</div>        

{{-- 六角形作成 --}}
<div class="circle-container" id="circle"></div>

{{-- モーダル差し込み --}}
<div id="start-modal-root"></div>

{{-- controllerから得たデータを受け取り、加工し、resources/js/game_stages.jsで使用 --}}
<script>  

    window.stage_urls = @json($stages);

    console.log('stage_urls', stage_urls);
    
    // 各ステージのid配列
    window.played_stage_ids = @json($played_stage_ids);

    console.log('played_stage_ids', played_stage_ids);

</script>

@endsection

