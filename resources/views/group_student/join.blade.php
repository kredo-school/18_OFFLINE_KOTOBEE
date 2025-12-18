@extends('layouts.app')

@push('styles')
    @vite(['resources/css/group_student_join.css']);
@endpush


@section('content')
    <div class="join-group-container">
        <h1 class="join-group-title">Join Group</h1>

        <div class="join-group-info">
            <div class="join-group-name">{{ $group->name }}</div>
            <div class="join-group-owner">{{ $group->owner->name }}</div>
        </div>

        @if (session('message'))
            <div class="join-group-status">
                {{ session('message') }}
            </div>
        @endif

        <p class="join-group-description">
            Enter the secret word to join the group
        </p>

        <form action="{{ route('group.join.submit', $group) }}" method="POST" class="join-group-form">
            @csrf

            <input
                type="text"
                name="secret_word"
                value="{{ old('secret_word') }}"
                placeholder="Enter secret word"
                class="join-group-input"
            >

            @error('secret_word')
                <div class="join-group-error">{{ $message }}</div>
            @enderror

            <button type="submit" class="join-group-submit">
                Submit
            </button>
        </form>        
    </div>
@endsection
