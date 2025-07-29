@extends('layout.app')

@section('content')
    <div class="container">
        {{-- @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif --}}

        <div class="w-2/3 mx-auto">
            @include('layout.server_response')
        </div>

        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Roles</h1>
            <a href="{{ route('roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+
                Add Role</a>
        </div>

        <!-- Tabs -->
        <ul class="flex border-b border-gray-300" role="tablist">
            <li>
                <button id="tab-1" role="tab" aria-controls="panel-1" aria-selected="true"
                    class="px-4 py-2 -mb-px text-sm font-medium border-b-2 border-blue-600 text-blue-600 focus:outline-none cursor-pointer"
                    data-tab-target="panel-1">
                    Enabled Roles
                </button>
            </li>
            <li>
                <button id="tab-2" role="tab" aria-controls="panel-2" aria-selected="false"
                    class="px-4 py-2 -mb-px text-sm font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent focus:outline-none cursor-pointer"
                    data-tab-target="panel-2">
                    Disabled Roles
                </button>
            </li>
        </ul>

        <!-- Panels -->
        <div id="panel-1" role="tabpanel" aria-labelledby="tab-1" class="p-4 text-sm">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-blue-200 text-gray-700 uppercase text-sm leading-normal">
                        <th class="text-left py-2 px-4" style="max-width:100px;">Role Name</th>
                        <th class="text-left py-2 px-4">Allowed Permissions</th>
                        <th class="text-left py-2 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $enabledRoles = $roles->filter(function ($item) {
                            return $item->enabled;
                        });
                    @endphp
                    @foreach ($enabledRoles as $role)
                        <tr class="border-b [border-color:#ccc] hover:bg-gray-50">
                            <td class="py-2 px-4">
                                {{ $role->role_name }}
                                <p class="text-xs text-gray-500">{{ $role->role_description }}</p>
                            </td>
                            <td class="py-2 px-4 space-between">
                                @php
                                    $tasks = config('tasks');
                                    // Build a flat associative array: ['task_name' => 'label', ...]
                                    $tasksMap = [];
                                    foreach ($tasks as $item) {
                                        $tasksMap[$item['task_name']] = $item['label'];
                                    }

                                    $assignedPermissions = array_map(function ($permission_name) use ($tasksMap) {
                                        return $tasksMap[$permission_name] ?? '';
                                    }, $role->permissions());
                                @endphp

                                {{-- <div class="grid grid-cols-3 gap-2"> --}}
                                @foreach ($assignedPermissions as $permission)
                                    <span class="m-1 py-1 px-2 bg-blue-400 text-white rounded-2xl text-xs">
                                        {{ $permission }}
                                    </span>
                                @endforeach
                                {{-- </div> --}}

                            </td>
                            <td class="py-2 px-4 space-x-2">
                                <a href="{{ route('roles.edit', $role->_id) }}"
                                    class="text-blue-600 hover:underline cursor-pointer">Edit</a>
                                |
                                @if ($role->enabled)
                                    <form action="{{ route('roles.destroy', $role->_id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Are you sure to disable this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline cursor-pointer">Disable</button>
                                    </form>
                                @else
                                    <form action="{{ route('roles.enable', $role->_id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Are you sure to restore?')">
                                        @csrf
                                        <button class="text-green-600 hover:underline cursor-pointer">Enable</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div id="panel-2" role="tabpanel" aria-labelledby="tab-2" class="hidden p-4 text-sm">
            <table class="min-w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-blue-200 text-gray-700 uppercase text-sm leading-normal">
                        <th class="text-left py-2 px-4" style="max-width:100px;">Role Name</th>
                        <th class="text-left py-2 px-4">Allowed Permissions</th>
                        <th class="text-left py-2 px-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $disabledRoles = $roles->filter(function ($role) {
                            return !$role->enabled;
                        });
                    @endphp
                    @foreach ($disabledRoles as $role)
                        <tr class="border-b [border-color:#ccc] hover:bg-gray-50">
                            <td class="py-2 px-4">
                                {{ $role->role_name }}
                                <p class="text-xs text-gray-500">{{ $role->role_description }}</p>
                            </td>
                            <td class="py-2 px-4">
                                @php
                                    $tasks = config('tasks');
                                    // Build a flat associative array: ['task_name' => 'label', ...]
                                    $tasksMap = [];
                                    foreach ($tasks as $item) {
                                        $tasksMap[$item['task_name']] = $item['label'];
                                    }

                                    $assignedPermissions = array_map(function ($permission_name) use ($tasksMap) {
                                        return $tasksMap[$permission_name] ?? '';
                                    }, $role->permissions());
                                @endphp

                                {{-- <div class="grid grid-cols-3 gap-2"> --}}
                                @foreach ($assignedPermissions as $permission)
                                    <span class="m-2 py-1 px-2 bg-blue-400 text-white rounded-2xl text-xs">
                                        {{ $permission }}
                                    </span>
                                @endforeach
                                {{-- </div> --}}

                            </td>
                            <td class="py-2 px-4 space-x-2">
                                <a href="{{ route('roles.edit', $role->_id) }}"
                                    class="text-blue-600 hover:underline cursor-pointer">Edit</a>
                                |
                                @if ($role->enabled)
                                    <form action="{{ route('roles.destroy', $role->_id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Are you sure to disable this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline cursor-pointer">Disable</button>
                                    </form>
                                @else
                                    <form action="{{ route('roles.enable', $role->_id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Are you sure to restore?')">
                                        @csrf
                                        <button class="text-green-600 hover:underline cursor-pointer">Enable</button>
                                    </form>
                                @endif
                                {{-- |
                            <a href="{{ route('permissions.assignPermissionsToRole', $role->_id) }}"
                                class="text-gray-600 hover:underline cursor-pointer">Permissions</a> --}}

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('javascripts')
    <script>
        // Activate the clicked tab and show the matching panel
        document.querySelectorAll('[role="tab"]').forEach(tab => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.tabTarget; // e.g. "panel-2"

                // 1. Update selected state on every tab
                document.querySelectorAll('[role="tab"]').forEach(t => {
                    const isActive = t === tab;
                    t.classList.toggle('border-blue-600', isActive);
                    t.classList.toggle('text-blue-600', isActive);
                    t.classList.toggle('text-gray-600', !isActive);
                    t.classList.toggle('border-transparent', !isActive);
                    t.setAttribute('aria-selected', isActive);
                });

                // 2. Show / hide panels
                document.querySelectorAll('[role="tabpanel"]').forEach(panel => {
                    panel.classList.toggle('hidden', panel.id !== target);
                });
            });
        });
    </script>
@endsection
