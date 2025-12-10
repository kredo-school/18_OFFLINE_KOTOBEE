@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Grammar Game stages</h2>
    <table class="table table-boreded">
        <thead>
            <tr>
                <th>ID</th>
                <th>created_by_admin_id</th>
                <th>stage_id</th>
                <th>note</th>
                <th>image_url</th>
                <th>correct_sentence</th>
                <th></th>
            </tr>            
        </thead>

        <tbody>
            @foreach ($stages as $stage)
                <tr>
                    <td>{{ $stage->id }}</td>
                    <td>{{ $stage->created_by_admin_id }}</td>
                    <td>{{ $stage->stage_id }}</td>
                    <td>{{ $stage->note }}</td>
                    <td>{{ $stage->image_url }}</td>
                    <td>{{ $stage->correct_sentence }}</td>
                    <td>
                        @if ($stage->id % 5 == 1)
                            <a href="{{ route('grammar.play', $stage->stage_id )}}" class="btn btn-primary btn-sm">
                                Start
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>    

</div>


@endsection