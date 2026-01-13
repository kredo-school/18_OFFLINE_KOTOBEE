@extends('layouts.app')

@section('content')
<div style="max-width:600px; margin:40px auto; background:#fff; padding:30px; border-radius:15px;">

    <h2 style="color:#c00;">⚠ Delete Group</h2>

    <p>
        You are about to <strong>permanently delete</strong> the following group.<br>
        <strong>This action cannot be undone.</strong>
    </p>

    <div style="margin:20px 0; padding:15px; background:#f5f5f5; border-radius:10px;">
        <p><strong>Group Name:</strong> {{ $group->name }}</p>
        <p><strong>Note:</strong> {{ $group->note ?? 'None' }}</p>
    </div>

    <form action="{{ route('groups.destroy', $group) }}" method="POST"
          onsubmit="return confirm('Are you sure you want to delete this group?');">
        @csrf
        @method('DELETE')

        <button type="submit"
            style="background:#c00; color:#fff; padding:10px 20px; border:none; border-radius:8px;">
            Delete Group
        </button>

        <a href="{{ url()->previous() }}"
           style="margin-left:15px; color:#555;">
            Cancel
        </a>
    </form>
</div>
@endsection
