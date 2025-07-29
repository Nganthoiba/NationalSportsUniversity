@extends('layout.app')

@section('content')
    <div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Add New Department</h2>
        @include('departments._form', [
            'action' => route('departments.store'),
            'method' => 'POST',
        ])
    </div>
@endsection
