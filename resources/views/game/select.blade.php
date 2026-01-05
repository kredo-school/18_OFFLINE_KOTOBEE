@extends('layouts.app')

@section('content')

<div class="game-select-wrapper">

    <h1 class="game-select-title">Game Select</h1>

    {{-- Japanese subtitle --}}
    <div class="game-select-subtitle">
        ゲームをえらんでね！
    </div>

    <ul class="game-menu">
        <li>
            <a href="{{ route('kana.options') }}" class="game-menu-btn">
                1. Kana Game
            </a>
        </li>

        <li>
            <a href="{{ route('home')}}" class="game-menu-btn">
                2. Vocabulary Game
            </a>
        </li>

        <li>
            <a href="{{ route('grammar.stages') }}" class="game-menu-btn">
                3. Grammar Game
            </a>
        </li>
    </ul>

</div>

<style>
.game-select-wrapper {
    text-align: center;
    margin-top: 50px;
}

/* Title */
.game-select-title {
    font-size: 2rem;
    margin-bottom: 5px;
    color: #5a4a2f;   /* ★ loginと合わせた茶系 */
}

/* Subtitle (Japanese) */
.game-select-subtitle {
    font-size: 0.9rem;
    font-style: italic;
    color: #6b6b6b;
    margin-bottom: 30px;
}

/* Menu */
.game-menu {
    list-style: none;
    padding: 0;
}

.game-menu li {
    margin-bottom: 20px;
}

/* Buttons */
.game-menu-btn {
    display: inline-block;
    width: 260px;           /* ★ 横幅統一 */
    padding: 14px 0;
    background: #B67A20;
    color: white;
    border-radius: 10px;
    font-size: 1.3rem;
    text-decoration: none;
}

.game-menu-btn.disabled {
    background: #aaa;
    cursor: default;
}
</style>

@endsection
