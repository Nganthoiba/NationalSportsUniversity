@extends('layout.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-4 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Add New Role</h2>
        @include('roles._form', [
            'action' => route('roles.store'),
            'method' => 'POST',
        ])
    </div>
@endsection
