@props(['role' => null, 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div>
        <label for="role_name" class="block text-sm font-medium text-gray-700">Role Name<span
                class="text-red-600">*</span></label>
        <input type="text" name="role_name" id="role_name" value="{{ old('role_name', $role->role_name ?? '') }}"
            required
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
            @if (isset($role) && !$role->changeable) {{ 'readonly' }} @endif>
        @error('role_name')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
        @if (isset($role) && !$role->changeable)
            <div class="text-xs text-gray-600">#Note: This role name cannot be changed as it is created in-built.</div>
        @endif
    </div>

    <div class="mt-2">
        <label for="role_description" class="block text-sm font-medium text-gray-700">About the role</label>
        <input type="text" name="role_description" id="role_description"
            value="{{ old('role_description', $role->role_description ?? '') }}"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
        @error('role_description')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-2">
        <label for="#" class="block text-sm font-medium text-gray-700">Assign permissions</label>
        <div class="mt-2 ">{{-- permission-grid --}}
            @php
                $available_permissions = collect(config('permissions'))->sortBy('label')->values()->all();

            @endphp

            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-12 px-4 py-3 text-left"></th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Permission Name</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">About</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($available_permissions as $task)
                        @php
                            $checked = isset($role)
                                ? (in_array($task['permission_name'], $role->permissions())
                                    ? 'checked'
                                    : '')
                                : '';
                        @endphp
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-4 py-2">
                                <input type="checkbox" name="permission_names[]" id="{{ $task['permission_name'] }}"
                                    value="{{ $task['permission_name'] }}" {{ $checked }}
                                    class="form-checkbox text-blue-600 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </td>
                            <td class="px-4 py-2">
                                <label for="{{ $task['permission_name'] }}"
                                    class="cursor-pointer font-semibold text-sm text-gray-800"
                                    title="{{ $task['description'] }}">
                                    {{ $task['label'] }}
                                </label>
                            </td>
                            <td class="px-4 py-2 text-gray-600 text-sm">
                                {{ $task['description'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="flex justify-end space-x-4 gap-2 mt-2">
        <a href="{{ route('roles.index') }}"
            class="inline-flex items-center px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
            Cancel
        </a>
        <button type="submit"
            class="inline-flex items-center px-4 py-2 text-sm text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow cursor-pointer">
            Save
        </button>
    </div>
</form>
