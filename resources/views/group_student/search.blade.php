@extends('layouts.app')

@push('styles')
    @vite(['resources/css/group_student_search.css']);
@endpush

@section('content')
   

    <div class="group-search-container">
        <h3 class="group-search-title">Group_Search</h3>

        {{-- 検索フォーム（GET） --}}
        <form action="{{ route('group.search') }}" method="GET" class="group-search-form">
            <div class="group-search-form-inner">
                <input
                    type="text"
                    name="keyword"
                    value="{{ $keyword ?? '' }}"
                    placeholder="Search"
                    class="group-search-input"
                />
                <button type="submit" class="group-search-button">
                    検索
                </button>
            </div>
        </form>

        {{-- 検索結果 --}}
        @forelse ($groups as $group)
            <a href="{{ route('group.join', $group) }}" class="group-card-link">
                <div class="group-card">
                    <div class="group-card-title">
                        {{ $group->name }}
                    </div>
                    <div class="group-card-note">
                        {{ $group->owner->name }}
                    </div>

                    <div class="group-card-action">
                        <span class="group-card-action-button">
                            このグループを見る →
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <p class="group-empty-text">該当するグループがありません。</p>
        @endforelse
    </div>

    
@endsection