@extends('layouts.app')

{{-- admin sidebar --}}
@section('admin_sidebar')
    @include('layouts.admin_side_bar')
@endsection

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

        .question-card {
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
            margin-bottom: 10px;
            margin-top: 20px;
        }

        .word-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            border-radius: 8px;
            border: none;
            margin-bottom: 10px;
        }

        .preview-btn {
            background: #4CAF50;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="stage-box">
        <h2>Stage Question Creation</h2>

        @if (session('success'))
            <p style="color:green">{{ session('success') }}</p>
        @endif

        <form method="POST" action="{{ route('admin.vocab.store') }}" enctype="multipart/form-data">
            @csrf

            <label>Game Type</label>
            <select name="game_id" required onchange="handleGameChange(this)">
                <option value="2">Multiple-choice question</option>
                <option value="3">Grammar question</option>
            </select>



            @for ($i = 0; $i < 5; $i++)
                <div class="question-card">
                    <div class="question-header">Q{{ $i + 1 }}</div>

                    <label>Title / Note</label>
                    <input type="text" name="questions[{{ $i }}][note]" placeholder="e.g. flute①">

                    <label>Correct Word</label>
                    <input type="text" name="questions[{{ $i }}][word]" required>

                    <label>Part of Speech</label>
                    <select name="questions[{{ $i }}][part_of_speech]" required>
                        <option value="">Select</option>
                        <option value="1">Noun</option>
                        <option value="2">Verb</option>
                        <option value="3">Adjective</option>
                        <option value="4">Adverb</option>
                        <option value="5">Particle</option>
                        <option value="6">Other</option>
                    </select>

                    <div class="image-box">
                        <img id="preview-img-{{ $i }}" class="word-image">
                    </div>

                    <input type="file" name="questions[{{ $i }}][image]" accept="image/*"
                        onchange="previewImage(event, {{ $i }})" required>

                    <button type="button" class="preview-btn" onclick="alert('画像・答え・品詞はゲーム画面と同じ表示になります')">
                        Preview
                    </button>
                </div>
            @endfor

            <button type="submit" class="preview-btn">Save Stage</button>
        </form>
    </div>

    <script>
        function previewImage(event, index) {
            const file = event.target.files[0];
            document.getElementById('preview-img-' + index).src = URL.createObjectURL(file);
        }
    </script>

    <script>
        function handleGameChange(select) {
            if (select.value == '3') {
                // Grammar question 用ページへ
                window.location.href = "{{ route('admin.grammar.create') }}";
            }

            if (select.value == '2') {
                // 今の vocab 作成ページ
                window.location.href = "{{ route('admin.vocab.create') }}";
            }
        }
    </script>
@endsection
