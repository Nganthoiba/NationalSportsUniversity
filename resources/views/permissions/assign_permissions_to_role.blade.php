@extends('layout.app')
@section('content')
    <div class="container">

        <div class="w-3/4 mx-auto mt-2 bg-white rounded-lg shadow-lg p-6">
            <h4 class="border-b border-gray-300 pb-2 font-semibold text-gray-500">Assign permissions to the role:</h4>

            <div class="my-2">
                <strong>Role Name:</strong>
                <span>{{ $role->role_name }}</span>
            </div>
            <div class="my-2">
                <strong>Description:</strong>
                <span>{{ $role->role_description }}</span>
            </div>

            <h4 class="border-b border-gray-300 pb-2 font-semibold text-gray-500">Available permissions:</h4>
            <div class="mt-2 permission-grid">
                @foreach ($permissions as $permission)
                    <div class="permission-item">
                        <input type="checkbox" name="permissions[]" id="permission_{{ $permission->_id }}"
                            value="{{ $permission->_id }}">
                        <label for="permission_{{ $permission->_id }}">{{ $permission->label }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
