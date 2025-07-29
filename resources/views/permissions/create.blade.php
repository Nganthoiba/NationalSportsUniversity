@extends('layout.app')

@section('content')
    <div class="container">
        <div class="w-3/4 mx-auto p-4 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-4">Create Permission</h2>

            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf

                @include('permissions._form', ['permission' => null])
                <div class="text-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded cursor-pointer">Create</button>
                </div>
            </form>
        </div>
    </div>
@endsection
