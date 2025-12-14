@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2>支払いを確認しています...</h2>

    <p class="mt-3">
        PayPalでの支払い完了を確認中です。<br>
        この画面は自動で切り替わります。
    </p>

    <div class="spinner-border mt-4" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script>
setInterval(() => {
    fetch("{{ route('group.pending.status') }}")
        .then(res => res.json())
        .then(data => {
            if (data.status === 'active') {
                window.location.href = "{{ route('group.dashboard') }}";
            }
        });
}, 5000);
</script>
@endsection
