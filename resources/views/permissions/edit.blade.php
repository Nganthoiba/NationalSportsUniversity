@extends('layout.app')

@section('content')
    <div class="container">
        <div class="w-3/4 mx-auto p-4 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-2 text-gray-600">Edit Permission</h2>

            <form action="{{ route('permissions.update', $permission->_id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('permissions._form', ['permission' => $permission])
                <div class="text-end">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded cursor-pointer">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
