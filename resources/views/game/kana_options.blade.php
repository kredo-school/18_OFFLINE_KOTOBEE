@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Kana Game Options</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mode</th>
                <th>Order</th>
                <th>Script</th>
                <th>Subtype</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($settings as $setting)
            <tr>
                <td>{{ $setting->id }}</td>
                <td>{{ $setting->mode }}</td>
                <td>{{ $setting->order_type }}</td>
                <td>{{ $setting->script }}</td>
                <td>{{ $setting->subtype }}</td>
                <td>
                    <a href="{{ route('kana.start', $setting->id) }}" 
                       class="btn btn-primary btn-sm">
                        Start
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
