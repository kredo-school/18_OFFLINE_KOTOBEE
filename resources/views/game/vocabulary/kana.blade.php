@extends('layouts.app')

@push('scripts')
    @vite('resources/js/vocab_result_modal.js')
@endpush

@push('scripts')
    @vite('resources/css/style_kana.css')
@endpush

@section('content')


    <!-- 共通：ゲーム結果モーダル -->
    <div id="result-modal" style="display:none;">
        <h2>Result</h2>

        <div id="rank-content"></div>

        <div class="modal-buttons">
            <button id="again-btn" class="kb-btn-again">Again</button>
            <a href="{{ route('game.select') }}" class="kb-btn-back">Back</a>
        </div>
    </div>





    <script>
        console.log("session correct raw:", "{{ session('correct') }}");
        console.log("session last_answer:", "{{ session('last_answer') }}");
        console.log("POST answer:", "{{ old('answer') }}");
    </script>



    <div class="game-wrapper">

        <h2 class="title">じゅんばんに ならべてね！</h2>

        <!-- 画像（正解時は青枠） -->
        @if ($question)
            <div class="image-box {{ session('correct') ? 'correct-border' : '' }}">
                <img src="{{ asset($question->image_url) }}" class="word-image">

            </div>
        @endif


        {{-- 回答フォーム --}}
        <form id="kanaForm" method="POST" action="{{ route('vocab.checkKana') }}">
            @csrf
            <input type="hidden" name="answer" id="answerInput">
        </form>

        <!-- 並べ替えの回答表示（復元対応） -->
        <div id="answer" class="answer-area">
            @if (session('last_answer'))
                @foreach (mb_str_split(session('last_answer')) as $char)
                    <div class="kana-box">{{ $char }}</div>
                @endforeach
            @endif
        </div>

        <!-- バラバラの文字（既に使用済みの文字は除外） -->
        <div id="choices">
            @php
                $used = session('last_answer') ? mb_str_split(session('last_answer')) : [];
            @endphp

            @foreach ($shuffled as $char)
                @if (!in_array($char, $used))
                    <div class="choice-btn kana-box" onclick="addChar(this)">
                        {{ $char }}
                    </div>
                @endif
            @endforeach
        </div>

    </div>

    {{-- 不正解 --}}
    @if (session('error'))
        <div class="result-panel">
            <div class="result-text error">
                <span class="icon">✖</span> Try again!
            </div>

            
        </div>
    @endif

    {{-- 正解 --}}
    @if (session('correct'))
        <div class="result-panel">
            <div class="result-text success">
                <span class="icon-circle">✓</span>
                Great job!
            </div>


            <form method="POST" action="{{ route('vocab.next') }}">
                @csrf
                <button class="panel-btn">
                    continue
                </button>
            </form>
        </div>
    @endif



    <script>
        function addChar(el) {
            // 正解後は操作禁止
            @if (session('correct'))
                return;
            @endif

            // 回答欄へ移動
            document.getElementById('answer').appendChild(el.cloneNode(true));
            el.remove();

            // 回答の文字配列
            let chars = [];
            document.querySelectorAll('#answer .kana-box').forEach(box => {
                chars.push(box.innerText.trim());
            });

            // hidden にセット
            document.getElementById('answerInput').value = chars.join('');

            // 全部選んだら送信（自動）
            if (chars.length === {{ count($chars) }}) {
                document.getElementById('kanaForm').submit();
            }
        }
    </script>

    @if (session('error'))
        <script>
            // ❌ 不正解 → 回答欄を空にして、選択肢を再生成する
            window.addEventListener('DOMContentLoaded', () => {
                const answerArea = document.getElementById('answer');
                const choicesArea = document.getElementById('choices');

                // 回答欄を空にする
                answerArea.innerHTML = '';

                // 選択肢も Blade から再レンダリングされてるはずなので何もしなくてよい。
                // （すでに元の $shuffled がレンダリングされてる）

                // これで再度選択できるようになる！
            });
        </script>
    @endif

 
    @if (session('correct') && $isLast)
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                console.log("finish 呼び出し準備OK");

                try {
                    const res = await fetch("{{ route('vocab.finish') }}");
                    console.log("finish fetch:", res);

                    const data = await res.json();
                    console.log("finish json:", data);

                    showVocabResult(data);
                } catch (e) {
                    console.error("finish error:", e);
                }
            });
        </script>
    @endif

    <script src="/js/vocab_result_modal.js"></script>



    <script>
        console.log("kana.blade 読み込み OK");
    </script>

    <script>
        console.log("correct?", "{{ session('correct') }}");
        console.log("isLast?", "{{ $isLast }}");
    </script>




@endsection
