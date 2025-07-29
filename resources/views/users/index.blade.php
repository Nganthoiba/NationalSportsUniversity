@extends('layout.app')
@section('content')
    <div class="container">
        <div class="w-1/2 mx-auto">@include('layout.server_response')</div>
        <div class="flex justify-between mb-2">
            <h5 class="text-gray-600 font-bold text-2xl">{{ $title }}<h5>
                    <div>
                        @php
                            $route = Auth::user()->hasRole('Super Admin')
                                ? route('users.create-university-admin')
                                : route('users.create-university-user');
                        @endphp
                        <a href="{{ $route }}" class="btn btn-primary">+ Add User</a>
                    </div>
        </div>
        <table class="table_style">
            <thead>
                <tr>
                    <th class="px-2">#</th>
                    <th class="px-2">Admin Name</th>
                    @if ($userType == 'admins')
                        <th class="px-2">University</th>
                    @else
                        <th class="px-2">Assigned Roles</th>
                    @endif
                    <th class="px-2">Created By</th>
                    <th class="px-2">Created At</th>
                    <th class="px-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}.</td>
                        <td>
                            <div class="font-bold text-gray-600">{{ $user->full_name }}</div>
                            <div class="email text-gray-500">{{ $user->email }}</div>
                        </td>
                        @if ($userType == 'admins')
                            <td>{{ $user->university_name }}</td>
                        @else
                            <td>

                                <div>
                                    @foreach ($user->getAssignedRoles() as $role)
                                        <span class="badge badge-default">{{ $role }}</span>
                                    @endforeach
                                </div>
                            </td>
                        @endif
                        <td>
                            <div>{{ $user->creator->full_name }}</div>
                            <div class="text-gray-500">{{ $user->creator->email }}</div>
                        </td>
                        <td>{{ date('d M, Y', strtotime($user->created_at)) }}</td>
                        <td class="text-end">
                            <form action="{{ route('users.enableOrDisable') }}" method="POST" class="enable_disable_form">
                                @if ($userType == 'staffs')
                                    <button type="button" data-toggle="modal" data-target="assignUserRoleModal"
                                        data-assigned-roles="{{ json_encode($user->getRoles(['id', 'role_name'])) }}"
                                        data-user-id="{{ $user->id }}"
                                        class="btn btn-primary cursor-pointer text-gray-600 hover:text-blue-700 
                                    assign-role-btn">Change
                                        Roles</button>
                                @endif
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">

                                @if ($user->enabled)
                                    <input type="hidden" name="enabled" value="false">
                                    <button class="btn btn-warning">Disable User</button>
                                @else
                                    <input type="hidden" name="enabled" value="true">
                                    <button class="btn btn-success">Enable User</button>
                                @endif
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="alert-box bg-white shadow-lg rounded-lg p-4">
        <button type="button"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl cursor-pointer close-alert">
            &times;
        </button>
        <div>
            Are You sure?
        </div>
    </div>

    @if ($userType == 'staffs')
        <div id="assignUserRoleModal"
            class="modal fixed inset-0 hidden items-center justify-center z-50 transition-opacity duration-300 p-6"
            style="background-color: rgba(152, 167, 152, 0.3);">

            <div
                class="modal-body relative bg-gray-100 rounded-lg p-4 w-[50%] max-w-[1200px] shadow-lg transform scale-95 opacity-0 transition-all duration-300">
                <!-- Close buttons -->
                <button type="button"
                    class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-2xl cursor-pointer close-modal">
                    &times;
                </button>
                <form action="{{ route('userrolemappings.finalizeAssignment') }}" name="role_assignment_form"
                    method="POST">
                    <div class="py-3 border-b border-gray-300">
                        <h1 class="text-gray-600">Assign one or more user Role(s)</h1>
                    </div>
                    @csrf
                    <input type="hidden" name="selected_user_id" value="" />
                    @foreach ($roles as $role)
                        <div class="mt-2">
                            <input type="checkbox" name="selected_roles[]" value="{{ $role->id }}"
                                id="role_{{ $role->id }}">
                            <label for="role_{{ $role->id }}">{{ $role->role_name }}</label>
                            <div class="text-xs">{{ $role->role_description }}</div>
                        </div>
                    @endforeach
                    <div class="mt-2 text-end"><button class="btn" type="submit">Assign</button></div>
                </form>
            </div>
        </div>
    @endif
@endsection
@section('javascripts')
    <script>
        let assignRoleBtns = document.querySelectorAll("button.assign-role-btn");
        assignRoleBtns.forEach(element => {
            element.addEventListener("click", (e) => {
                /* document.querySelectorAll("input[name='assigned_roles[]']:checked").forEach((checkbox) => {
                    checkbox.checked = false;
                }); */
                document.forms['role_assignment_form'].reset();
                var assignedRoles = JSON.parse(element.getAttribute("data-assigned-roles"));
                document.forms['role_assignment_form'].selected_user_id.value = element.getAttribute(
                    "data-user-id");

                assignedRoles.forEach(role => {
                    document.getElementById("role_" + role.id).checked = true;
                });
            });
        });

        document.querySelectorAll("form.enable_disable_form").forEach(form => {
            form.onsubmit = (event) => {
                event.preventDefault();
                if (!confirm("Are you sure?")) {
                    return;
                }
                form.submit();
            }
        });
    </script>
@endsection
