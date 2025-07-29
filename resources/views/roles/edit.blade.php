@extends('layout.app')

@section('content')
    <div class="max-w-2xl mx-auto mt-4 bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Edit Role</h2>
        @include('roles._form', [
            'role' => $role,
            'action' => route('roles.update', $role->_id),
            'method' => 'PUT',
        ])
    </div>
@endsection
