@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">

<div class="game-wrapper">

    <div class="header-bar">
        <img src="/images/logo.png" class="logo">
    </div>

    <h2 class="title">こたえをえらんでね！</h2>

    <div class="image-box {{ session('correct') ? 'correct-border' : '' }}">
        <img src="{{ $question->image_url }}" class="word-image">
    </div>

    <form id="choiceForm" method="POST" action="{{ route('vocab.checkChoice') }}">
        @csrf
        <input type="hidden" name="answer" id="answerInput">

        @foreach ($choices as $c)
            <button type="button" class="choice-btn" onclick="choose('{{ $c }}', this)">
                {{ $c }}
            </button>
        @endforeach
    </form>

    {{-- × 不正解 --}}
    @if (session('error'))
        <div class="message error">
            ✖ Try again!
        </div>
    @endif

    {{-- ✔ 正解 --}}

    @if(session('correct'))
    <div class="message success">
            ✔ Grate job!
        </div>

    <form method="POST" action="{{ route('vocab.next') }}">
        @csrf
        <button class="continue-btn">CONTINUE</button>
    </form>
@endif

</div>

<script>
function choose(value, btn) {
    // ボタン色変更
    document.querySelectorAll('.choice-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');

    // フォーム送信
    document.getElementById('answerInput').value = value;
    document.getElementById('choiceForm').submit();
}

function goNext() {
    window.location.href = "{{ route('vocab.show') }}";
}
</script>

@endsection
