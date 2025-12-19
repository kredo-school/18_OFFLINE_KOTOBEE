@extends('layouts.app')


@push('styles')
    @vite('resources/css/grammar.css')
@endpush

@section('content')
<div class="grammar-wrapper">
    <div class="header">
        <a href="{{ route('profile') }}" class="back-arrow">&#8592;</a>
        <h1>Grammar</h1>
    </div>

    @foreach($stages as $stageName => $sentences)
        <div class="stage">
            <h2>{{ $stageName }}</h2>
            <div class="words">
                @foreach($sentences as $sentence)
                    <div class="word-card">{{ $sentence }}</div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
