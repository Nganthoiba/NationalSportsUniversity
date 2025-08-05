@extends('layout.app')

@section('content')
    <div class="container max-w-7xl mx-auto py-6">

        {{-- Alerts --}}
        <div class="w-full max-w-2xl mx-auto mb-6">
            @include('layout.server_response')
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-semibold leading-tight">Roles</h1>
                <p class="text-sm text-gray-600 mt-1">Manage enabled and disabled role definitions and permissions.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <div class="relative">
                    <input type="text" id="role-search" placeholder="Search roles..."
                        class="border border-gray-300 rounded-md px-3 py-2 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        aria-label="Search roles">
                    <div class="absolute inset-y-0 right-2 flex items-center pointer-events-none">
                        <!-- simple search icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 19a8 8 0 100-16 8 8 0 000 16zm6-2l4 4" />
                        </svg>
                    </div>
                </div>
                <a href="{{ route('roles.create') }}"
                    class="inline-flex items-center gap-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-500">
                    <span class="text-lg font-medium">+</span> Add Role
                </a>
            </div>
        </div>

        <!-- Tabs -->
        @php
            $enabledRoles = $roles->filter(fn($r) => $r->enabled);
            $disabledRoles = $roles->filter(fn($r) => !$r->enabled);
            $tasks = config('permissions');
            $tasksMap = [];
            foreach ($tasks as $item) {
                $tasksMap[$item['task_name']] = $item['label'];
            }
        @endphp

        <div class="mb-4">
            <div role="tablist" aria-label="Role categories" class="flex flex-wrap gap-2 border-b border-gray-200">
                <button data-tab-target="panel-1" role="tab" aria-controls="panel-1" aria-selected="true"
                    class="px-4 py-2 -mb-px text-sm font-medium border-b-2 border-blue-600 text-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                    id="tab-1">
                    Enabled Roles <span class="ml-1 text-xs text-gray-500">({{ $enabledRoles->count() }})</span>
                </button>
                <button data-tab-target="panel-2" role="tab" aria-controls="panel-2" aria-selected="false"
                    class="px-4 py-2 -mb-px text-sm font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                    id="tab-2">
                    Disabled Roles <span class="ml-1 text-xs text-gray-500">({{ $disabledRoles->count() }})</span>
                </button>
            </div>
        </div>

        <!-- Panels -->
        <div class="space-y-8">
            <div id="panel-1" role="tabpanel" aria-labelledby="tab-1" class="p-4 text-sm">
                @if ($enabledRoles->isEmpty())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 text-yellow-800">
                        No enabled roles found. <a href="{{ route('roles.create') }}" class="text-blue-600 underline">Create
                            one</a>.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white shadow rounded-lg">
                            <thead>
                                <tr class="bg-blue-100 text-gray-700 uppercase text-xs tracking-wider">
                                    <th class="text-left py-3 px-4">Role Name</th>
                                    <th class="text-left py-3 px-4">Allowed Permissions</th>
                                    <th class="text-left py-3 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($enabledRoles as $role)
                                    @php
                                        $assignedPermissions = array_map(
                                            fn($name) => $tasksMap[$name] ?? '',
                                            $role->permissions(),
                                        );
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 align-top">
                                            <div class="font-semibold">{{ $role->role_name }}</div>
                                            @if ($role->role_description)
                                                <div class="text-xs text-gray-500 mt-1">{{ $role->role_description }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            @if (count(array_filter($assignedPermissions)))
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($assignedPermissions as $permission)
                                                        @if ($permission)
                                                            <div role="status"
                                                                class="flex items-center space-x-1 bg-blue-500 text-white rounded-full px-2 py-1 text-xs"
                                                                aria-label="Permission: {{ $permission }}">
                                                                <span>{{ $permission }}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-400 italic">No permissions assigned</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 space-x-2 whitespace-nowrap">
                                            <a href="{{ route('roles.edit', $role->_id) }}"
                                                class="text-blue-600 hover:underline">Edit</a>
                                            |
                                            <form action="{{ route('roles.destroy', $role->_id) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirmDisable(this, '{{ addslashes($role->role_name) }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Disable</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div id="panel-2" role="tabpanel" aria-labelledby="tab-2" class="hidden p-4 text-sm">
                @if ($disabledRoles->isEmpty())
                    <div class="bg-green-50 border border-green-200 rounded-md p-4 text-green-800">
                        No disabled roles. All roles are currently active.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white shadow rounded-lg">
                            <thead>
                                <tr class="bg-blue-100 text-gray-700 uppercase text-xs tracking-wider">
                                    <th class="text-left py-3 px-4">Role Name</th>
                                    <th class="text-left py-3 px-4">Allowed Permissions</th>
                                    <th class="text-left py-3 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach ($disabledRoles as $role)
                                    @php
                                        $assignedPermissions = array_map(
                                            fn($name) => $tasksMap[$name] ?? '',
                                            $role->permissions(),
                                        );
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 align-top">
                                            <div class="font-semibold">{{ $role->role_name }}</div>
                                            @if ($role->role_description)
                                                <div class="text-xs text-gray-500 mt-1">{{ $role->role_description }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            @if (count(array_filter($assignedPermissions)))
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach ($assignedPermissions as $permission)
                                                        @if ($permission)
                                                            <div role="status"
                                                                class="flex items-center space-x-1 bg-blue-500 text-white rounded-full px-2 py-1 text-xs"
                                                                aria-label="Permission: {{ $permission }}">
                                                                <span>{{ $permission }}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-400 italic">No permissions assigned</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 space-x-2 whitespace-nowrap">
                                            <a href="{{ route('roles.edit', $role->_id) }}"
                                                class="text-blue-600 hover:underline">Edit</a>
                                            |
                                            <form action="{{ route('roles.enable', $role->_id) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirmRestore(this, '{{ addslashes($role->role_name) }}')">
                                                @csrf
                                                <button type="submit"
                                                    class="text-green-600 hover:underline">Enable</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script>
        // Tab logic with keyboard support
        const tabs = Array.from(document.querySelectorAll('[role="tab"]'));
        const panels = Array.from(document.querySelectorAll('[role="tabpanel"]'));

        function activateTab(tab) {
            const target = tab.dataset.tabTarget;
            tabs.forEach(t => {
                const isActive = t === tab;
                t.setAttribute('aria-selected', isActive);
                t.classList.toggle('border-blue-600', isActive);
                t.classList.toggle('text-blue-600', isActive);
                t.classList.toggle('text-gray-600', !isActive);
                t.classList.toggle('border-transparent', !isActive);
                if (isActive) {
                    t.focus();
                }
            });
            panels.forEach(p => {
                p.classList.toggle('hidden', p.id !== target);
            });
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', () => activateTab(tab));
            tab.addEventListener('keydown', (e) => {
                const currentIndex = tabs.indexOf(tab);
                if (e.key === 'ArrowRight') {
                    activateTab(tabs[(currentIndex + 1) % tabs.length]);
                    e.preventDefault();
                } else if (e.key === 'ArrowLeft') {
                    activateTab(tabs[(currentIndex - 1 + tabs.length) % tabs.length]);
                    e.preventDefault();
                }
            });
        });

        // Role search (client-side filtering)
        document.getElementById('role-search').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(r => {
                const text = r.textContent.toLowerCase();
                r.style.display = text.includes(q) ? '' : 'none';
            });
        });

        // Confirmation helpers with fallback
        function confirmDisable(form, roleName) {
            return confirm(`Are you sure you want to disable the role "${roleName}"?`);
        }

        function confirmRestore(form, roleName) {
            return confirm(`Are you sure you want to restore the role "${roleName}"?`);
        }
    </script>
@endsection
