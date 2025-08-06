@extends('layout.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (Auth::user()->hasPermission('add_department'))
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Departments (विभाग)</h1>
                <a href="{{ route('departments.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+
                    Add Department</a>
            </div>
        @endif

        <table class="min-w-full bg-white shadow-md rounded-lg">
            <thead>
                <tr class="bg-blue-200 text-gray-700 uppercase text-sm leading-normal">
                    <th class="text-left py-2 px-4">#</th>
                    <th class="text-left py-2 px-4">Name</th>
                    <th class="text-left py-2 px-4">Name In Hindi</th>
                    <th class="text-left py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $key => $dept)
                    <tr class="border-b [border-color:#ccc] hover:bg-gray-50">
                        <td class="py-2 px-4">{{ $key + 1 }}.</td>
                        <td class="py-2 px-4">{{ $dept->dept_name }}</td>
                        <td class="py-2 px-4">{{ $dept->dept_name_in_hindi }}</td>
                        <td class="py-2 px-4 space-x-2">
                            <a href="{{ route('departments.edit', $dept->_id) }}"
                                class="text-blue-600 hover:underline">Edit</a>
                            @if ($dept->enabled)
                                <form action="{{ route('departments.destroy', $dept->_id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Are you sure to disable?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Disable</button>
                                </form>
                            @else
                                <form action="{{ route('departments.enable', $dept->_id) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Are you sure to restore?')">
                                    @csrf
                                    <button class="text-green-600 hover:underline">Restore</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
