@extends('layouts.app')

@section('content')
<!-- public/css に置いた場合 -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">


<div class="game-container" style="padding: 60px 20px;">

    <h1 class="result-title">Great job!</h1>
    <p class="result-time">{{ $time }} sec</p>

    <a href="/home" class="btn btn-primary"
       style="font-size:24px; padding:12px 40px; border-radius:15px;">
        ホームに戻る
    </a>
</div>

@endsection
