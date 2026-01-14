@extends('layouts.app')

{{-- admin sidebar --}}
@section('admin_sidebar')
    @include('layouts.admin_side_bar')
@endsection

@push('styles')
    @vite('resources/css/groupstudent.css')
@endpush

@section('content')

<div class="page-wrapper">

    <div class="card-box">

        <h3 class="card-title">List of member</h3>

        <!-- 上部操作エリア -->
        <div class="toolbar">
            <input type="text" class="search-input" placeholder="Search by name...">

            <select class="sort-select">
                <option>Ascending by request date</option>
                <option>Descending by request date</option>
            </select>

            <button class="btn-delete-selected">Delete Selected</button>
        </div>

        <!-- メンバー一覧 -->
        <table class="member-table">
            <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th class="text-right">delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>
                        <input type="checkbox">
                    </td>
                    <td>{{ $student->name }}</td>
                    <td class="text-right">
                        <!-- 削除ボタン -->
                        <button
                            class="btn-delete"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $student->id }}">
                            Delete
                        </button>

                        <!-- モーダル -->
                        <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">確認</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ $student->name }} を本当に削除しますか？
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">キャンセル</button>
                                        <form action="{{ route('groups.students.remove', [$group, $student]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">削除</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /モーダル -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>

@endsection
