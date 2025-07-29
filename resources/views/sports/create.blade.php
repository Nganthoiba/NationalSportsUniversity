@extends('layout.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Add New Sport</h2>
        @include('sport._form', [
            'action' => route('sports.store'),
            'method' => 'POST',
        ])
    </div>
@endsection
