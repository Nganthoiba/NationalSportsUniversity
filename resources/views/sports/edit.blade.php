@extends('layout.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Edit Sport</h2>
        @include('sports._form', [
            'sport' => $sport,
            'action' => route('sports.update', $sport->_id),
            'method' => 'PUT',
        ])
    </div>
@endsection
