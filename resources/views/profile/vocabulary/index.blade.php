{{-- resources/views/vocabulary/index.blade.php --}}
@extends('layouts.app')

@push('styles')
    @vite('resources/css/vocabulary.css')
@endpush

@section('content')
<div class="vocab-wrapper">
    <div class="header">
        <a href="{{ route('profile') }}" class="back-arrow">&#8592;</a>
        <h1>vocabulary</h1>
    </div>

    @foreach($stages as $stageName => $words)
        <div class="stage">
            <h2>{{ $stageName }}</h2>
            <div class="words">
                @foreach($words as $word)
                    <div class="word-card">{{ $word }}</div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
