@extends('layouts.app')

@section('content')

<div class="game-select-wrapper">

    <h1 class="game-select-title">Game Select</h1>

    <ul class="game-menu">
        <li>
            <a href="{{ route('kana.options') }}" class="game-menu-btn">
                1. Kana Game
            </a>
        </li>

        <li>
            <a href="#" class="game-menu-btn disabled">
                2. Vocabulary Game
            </a>
        </li>

        <li>
            <a href="#" class="game-menu-btn disabled">
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

.game-select-title {
    font-size: 2rem;
    margin-bottom: 30px;
}

.game-menu {
    list-style: none;
    padding: 0;
}

.game-menu li {
    margin-bottom: 20px;
}

.game-menu-btn {
    display: inline-block;
    padding: 12px 30px;
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
