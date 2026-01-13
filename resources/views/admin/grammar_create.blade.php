@extends('layouts.app')

{{-- admin sidebar --}}
@section('admin_sidebar')
    @include('layouts.admin_side_bar')
@endsection

@section('content')
@push('styles')
<style>
    body {
        background: #FFFFCE;
    }

    .stage-box {
        max-width: 900px;
        margin: 30px auto;
        background: #fff;
        border-radius: 20px;
        padding: 30px;
    }

    .question-box {
        background: #D9D9D9;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .question-header {
        background: #FF9900;
        color: #fff;
        padding: 10px 15px;
        border-radius: 10px;
        font-weight: bold;
        margin-bottom: 15px;
        font-size: 16px;
    }

    input, textarea, select {
        width: 100%;
        padding: 8px;
        border-radius: 8px;
        border: none;
        margin-bottom: 10px;
    }

    .btn, .preview-btn {
        background-color: #4CAF50 !important;
        color: #fff !important;
        padding: 6px 12px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .remove-btn {
        background: #e74c3c !important;
        color: #fff !important;
        padding: 4px 10px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-size: 12px;
        float: right;
    }

    .image-box {
        width: 200px;
        height: 200px;
        background: #fff;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin: 10px 0;
    }

    .image-box img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .block-card, .wrong-card {
        background: #f2f2f2;
        padding: 15px;
        border-radius: 12px;
        margin-bottom: 10px;
    }

    h4 {
        margin-top: 15px;
        margin-bottom: 8px;
    }
</style>
@endpush

<div class="stage-box">
    <h2>Create Grammar Stage (5 Questions = 1 Stage)</h2>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('admin.grammar.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Game Type</label>
        <select name="game_id" required onchange="handleGameChange(this)">
            <option value="3">Grammar Question</option>
            <option value="2">Multiple-choice Question</option>
        </select>

        @for ($q = 0; $q < 5; $q++)
        <div class="question-box">
            <div class="question-header">Q{{ $q + 1 }}</div>

            <label>Title / Note</label>
            <input type="text" name="questions[{{ $q }}][note]" placeholder="Optional note">

            <label>Image</label>
            <div class="image-box">
                <img id="preview-q{{ $q }}">
            </div>
            <input type="file" name="questions[{{ $q }}][image]" accept="image/*"
                   onchange="previewImage(event,'preview-q{{ $q }}')">

            <label>Correct Sentence</label>
            <textarea name="questions[{{ $q }}][correct_sentence]" required></textarea>

            <h4>Blocks (Order starts from 0)</h4>
            <div id="blocks-{{ $q }}"></div>
            <button type="button" class="btn" onclick="addBlock({{ $q }})">＋ Add Block</button>

            <h4>Wrong Answers (Optional)</h4>
            <div id="wrongs-{{ $q }}"></div>
            <button type="button" class="btn" onclick="addWrong({{ $q }})">＋ Add Wrong Answer</button>
        </div>
        @endfor

        <button type="submit" class="preview-btn">Save Stage</button>
    </form>
</div>

<script>
    function previewImage(event, id) {
        document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
    }

    const blockCount = [0,0,0,0,0];
    const wrongCount = [0,0,0,0,0];

    function addBlock(q) {
        const i = blockCount[q];
        const html = `
        <div class="block-card" id="block-${q}-${i}">
            <button type="button" class="remove-btn" onclick="removeBlock(${q},${i})">−</button>
            <strong>Order ${i}</strong>
            <input type="hidden" name="questions[${q}][blocks][${i}][order_number]" value="${i}">
            <input type="text" name="questions[${q}][blocks][${i}][block_text]" placeholder="Block text" required>
            <select name="questions[${q}][blocks][${i}][part_of_speech]" required>
                <option value="">Part of Speech</option>
                <option value="1">Noun</option>
                <option value="2">Verb</option>
                <option value="3">Adjective</option>
                <option value="4">Adverb</option>
                <option value="5">Particle</option>
                <option value="6">Other</option>
            </select>
        </div>`;
        document.getElementById(`blocks-${q}`).insertAdjacentHTML('beforeend', html);
        blockCount[q]++;
    }

    function removeBlock(q, i) {
        const el = document.getElementById(`block-${q}-${i}`);
        if (el) el.remove();
    }

    function addWrong(q) {
        const i = wrongCount[q];
        const html = `
        <div class="wrong-card" id="wrong-${q}-${i}">
            <button type="button" class="remove-btn" onclick="removeWrong(${q},${i})">−</button>
            <input type="text" name="questions[${q}][wrong_answers][${i}][wrong_order]" placeholder="Wrong order (e.g., 1,0,2)">
            <textarea name="questions[${q}][wrong_answers][${i}][wrong_sentence]" placeholder="Wrong sentence"></textarea>
            <input type="file" name="questions[${q}][wrong_answers][${i}][wrong_image]" accept="image/*">
        </div>`;
        document.getElementById(`wrongs-${q}`).insertAdjacentHTML('beforeend', html);
        wrongCount[q]++;
    }

    function removeWrong(q, i) {
        const el = document.getElementById(`wrong-${q}-${i}`);
        if (el) el.remove();
    }

    function handleGameChange(select) {
        if (select.value == '3') {
            window.location.href = "{{ route('admin.grammar.create') }}";
        }
        if (select.value == '2') {
            window.location.href = "{{ route('admin.vocab.create') }}";
        }
    }
</script>
@endsection
