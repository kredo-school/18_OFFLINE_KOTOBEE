@props(['group'])

<a class="back-btn"
   href="{{ route('group.dashboard', ['group_id' => $group->id]) }}"
   aria-label="Back to dashboard">
    <i class="fa-solid fa-arrow-left"></i>
</a>