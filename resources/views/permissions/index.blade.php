@extends('layout.app')
@section('css')
    <style>
        table.permissions-table {
            font-size: 0.87rem;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="flex justify-between">
            <h2 class="text-xl font-bold mb-4 text-gray-700">Permissions</h2>
            <a href="{{ route('permissions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">+
                Add
                Permission</a>
        </div>

        {{-- @if (session('success'))
            <div class="text-green-600">{{ session('success') }}</div>
        @endif --}}
        <div class="w-1/3 mx-auto">
            @include('layout.server_response')
        </div>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-auto permissions-table">
            <thead>
                <tr class="border-b border-gray-300 text-gray-700 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">#</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Label</th>
                    <th class="py-3 px-6 text-left">Description</th>
                    <th class="py-3 px-6 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $key => $perm)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $key + 1 }}.</td>
                        <td class="py-3 px-6">{{ $perm->name }}</td>
                        <td class="py-3 px-6">{{ $perm->label }}</td>
                        <td class="py-3 px-6">{{ $perm->description }}</td>
                        <td class="py-3 px-6">
                            <a href="{{ route('permissions.edit', $perm) }}" class="text-blue-500">Edit</a>
                            |
                            <form action="{{ route('permissions.destroy', $perm) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                @if ($perm->enabled)
                                    <input type="hidden" name="enabled" value="false" />
                                    <button type="submit" class="text-red-500 cursor-pointer">Disable</button>
                                @else
                                    <input type="hidden" name="enabled" value="true" />
                                    <button type="submit" class="text-green-500 cursor-pointer">Enable</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $permissions->links() }}
    </div>
@endsection
