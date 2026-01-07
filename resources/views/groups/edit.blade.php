@extends('layouts.app')

{{-- admin sidebar --}}
@section('admin_sidebar')
    @include('layouts.admin_side_bar')
@endsection

@push('styles')
    @vite(['resources/css/group_edit.css'])
@endpush


@section('content')
    {{-- @foreach ($group->users as $user)
        <p style="text-align: center;">{{ $user->name }}</p>
    @endforeach --}}    

    <div class="group-edit-wrap">        
        <h1>Edit Group</h1>

        <p class="subtitle">Modify your group settings</p>

        <form class="card" aria-label="Edit Group Form" method="post" action="{{ route('group.edit.process', ['id' => $group->id]) }}">
            @csrf

            <div class="field">
                <label class="label" for="group_name">Group Name</label>
                <input id="group_name" name="name" type="text" value="{{ $group->name }}" />
            </div>
      
            <div class="field">
                <label class="label" for="secret_word">Secret Word</label>
                <input id="secret_word" name="secret" type="text" value="{{ $group->secret }}" />
                <p class="hint">Share this word with your students. They’ll need it when joining the group</p>
            </div>
        
            <div class="field">
                <label class="label" for="memo">Group Memo</label>
                <textarea id="memo" name="note">{{ $group->note }}</textarea>
            </div>
        
            <div class="actions">
                <button class="btn btn-primary" type="button">Save</button>
                <button class="btn btn-ghost" type="button" disabled>Cancel</button>
            </div>

        </form>


    </div>
      
    
@endsection

