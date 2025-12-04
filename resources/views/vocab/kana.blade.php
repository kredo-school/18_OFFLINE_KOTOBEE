@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="game-wrapper">

    

    <h2 class="title">じゅんばんに ならべてね！</h2>

    <!-- 画像（正解時は青枠） -->
    <div class="image-box {{ session('correct') ? 'correct-border' : '' }}">
        <img src="{{ $question->image_url }}" class="word-image">
    </div>

    {{-- 回答フォーム --}}
    <form id="kanaForm" method="POST" action="{{ route('vocab.checkKana') }}">
        @csrf
        <input type="hidden" name="answer" id="answerInput">
    </form>

    <!-- 並べ替えの回答表示 -->
    <div id="answer" class="answer-area"></div>

    <!-- バラバラの文字 -->
    <div id="choices">
        @foreach($shuffled as $char)
            <div class="choice-btn kana-box" onclick="addChar(this)">
                {{ $char }}
            </div>
        @endforeach
    </div>

    {{-- × 不正解 --}}
    @if (session('error'))
        <div class="message error">
            ✖ Try again!
        </div>
    @endif

    {{-- ✔ 正解 --}}
    @if(session('correct'))
        <div class="message success">
            ✔ Great job!
        </div>

        <form method="POST" action="{{ route('vocab.next') }}">
            @csrf
            <button class="continue-btn">CONTINUE</button>
        </form>
    @endif

</div>

<script>
    function addChar(el) {
        // 正解後は触れない
        @if(session('correct'))
            return;
        @endif

        // 選択した要素を回答欄に移動
        document.getElementById('answer').appendChild(el.cloneNode(true));
        el.remove();

        // 回答を作成
        let chars = [];
        document.querySelectorAll('#answer .kana-box').forEach(box => {
            chars.push(box.innerText.trim());
        });

        document.getElementById('answerInput').value = chars.join('');

        // 正解チェックの送信
        if (chars.length === {{ count($chars) }}) {
            document.getElementById('kanaForm').submit();
        }
    }
</script>

@endsection
