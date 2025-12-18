@extends('layouts.app')

@push('styles')
    @vite(['resources/css/profile_edit.css'])
@endpush

@section('content')
<div class="edit-wrapper">

    {{-- Top Bar --}}
    <div class="edit-top-bar">
        <a href="{{ route('profile') }}">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2>Edit Profile</h2>
        <button type="submit" form="editProfileForm" class="save-btn">
            <i class="fa-solid fa-check green"></i>
        </button>
    </div>

    {{-- FORM --}}
    <form id="editProfileForm"
          method="POST"
          action="{{ route('profile.update') }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Avatar --}}
        <div class="edit-avatar">
            @if ($user->avatar_url)
                <img src="{{ asset('storage/' . $user->avatar_url) }}" class="avatar-img">
            @else
                <i class="fa-solid fa-circle-user default-avatar"></i>
            @endif

            <label class="camera-icon">
                <i class="fa-solid fa-camera"></i>
                <input type="file"
                       name="avatar"
                       accept="image/*"
                       hidden
                       onchange="previewAvatar(event)">
            </label>
        </div>

        <label>Name</label>
        <input type="text" name="name" value="{{ $user->name }}">

        <label>Email address</label>
        <input type="email" name="email" value="{{ $user->email }}">

        <label>Password</label>
        <input type="password" name="password">

        <label>Password confirmation</label>
        <input type="password" name="password_confirmation">
    </form>

    {{-- Delete --}}
    <form method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="delete-btn"
            onclick="return confirm('本当にアカウントを削除しますか？');">
            Delete account
        </button>
    </form>
</div>

<script>
function previewAvatar(event) {
    const reader = new FileReader();
    reader.onload = function () {
        let img = document.querySelector('.avatar-img');
        if (!img) {
            img = document.createElement('img');
            img.className = 'avatar-img';
            document.querySelector('.default-avatar')?.remove();
            document.querySelector('.edit-avatar').prepend(img);
        }
        img.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endsection
