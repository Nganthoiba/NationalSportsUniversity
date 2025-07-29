@extends('layout.app')
@section('css')
    <style>
        ul.user-list>li.active div {
            background-color: #b0c8fa;
        }

        .text-email {
            color: #6c6b6d;
        }

        .role-list li {
            border-bottom: #dbdadb 1px solid;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="flex">
            <div class="w-1/3 mr-2 border border-gray-300 p-4 bg-gray-100 rounded-sm shadow-md">
                <h5 class="border-b border-gray-300 pb-2 font-semibold text-gray-500">Click on a user to select</h5>
                <ul class="user-list overflow-y-auto p-2 mt-2 h-90">
                    @foreach ($users as $user)
                        <li data-user-id="{{ $user->_id }}" class="flex flex-row my-2 gap-1.5 items-center">
                            @php
                                $name_parts = explode(' ', $user->full_name);
                                $ch1 = substr($name_parts[0], 0, 1);
                                $ch2 = isset($name_parts[1]) ? substr($name_parts[1], 0, 1) : '';
                                $avatar = strtoupper($ch1 . $ch2);
                                $bgColors = [
                                    'bg-blue-500',
                                    'bg-green-500',
                                    'bg-red-500',
                                    'bg-purple-500',
                                    'bg-yellow-500',
                                ];
                                $bgColor = $bgColors[array_rand($bgColors)];
                            @endphp
                            <div
                                class="w-10 h-10 py-4 rounded-full text-white flex items-center justify-center text-lg font-semibold {{ $bgColor }} cursor-pointer">
                                <!-- Initials will be inserted here -->

                                {{ $avatar }}
                            </div>
                            <div class="text-sm  shadow-md p-2 bg-white rounded-2xl hover:bg-green-200 hover:cursor-pointer">
                                <h6>{{ $user->full_name }}</h6>
                                <p class="text-sm text-email">{{ $user->email }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="w-2/3 ml-2 border border-gray-300 p-4 bg-gray-100 rounded-sm shadow-md">
                <h5 class="border-b border-gray-300 pb-2 font-semibold text-gray-500">Select the roles that will be assigned
                    to the selected user:
                </h5>
                <div class="loader-wrapper overflow-y-auto ">
                    <div id="loader_layout" class="loader hidden" style="height:150px;width:150px;"></div>
                    <form action="{{ route('userrolemappings.finalizeAssignment') }}" name="role_assignment_form"
                        id="role_assignment_form">
                        @csrf
                        <input type="hidden" name="selected_user_id" id="selected_user_id" value="" required />
                        <ul class="role-list p-2">
                            @foreach ($roles as $role)
                                <li>
                                    <div class="my-2 flex gap-2.5 items-start">
                                        <div style="padding-top:5px;">
                                            <input type="checkbox" name="selected_roles[]" id="role_{{ $role->_id }}"
                                                value="{{ $role->_id }}">
                                        </div>
                                        <div class="p-0">
                                            <label for="role_{{ $role->_id }}"
                                                class="cursor-pointer text-sm">{{ $role->role_name }}</label>
                                            <div class="text-xs text-gray-500">{{ $role->role_description }}</div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="text-end px-4">
                            <button type="button" onclick="assignUserRole();" class="btn btn-primary">Assign Role</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script>
        var getAssignedRolesUrl = "{{ route('userrolemappings.getAssignedRoles', ':userId') }}";
        const role_assignment_form = document.forms['role_assignment_form'];

        document.querySelectorAll("ul.user-list>li").forEach(listElement => {
            listElement.addEventListener("click", (event) => {
                event.preventDefault();
                role_assignment_form.reset();
                var lastActiveItem = document.querySelector("ul.user-list>li.active");
                if (lastActiveItem) {
                    lastActiveItem.classList.remove("active");
                }
                listElement.classList.add("active"); //active set to current clicked element

                //alert(listElement.getAttribute("data-user-id"));

                role_assignment_form.selected_user_id.value = listElement.getAttribute("data-user-id");

                var url = getAssignedRolesUrl.replace(":userId", listElement.getAttribute(
                    "data-user-id"));

                document.querySelector("#loader_layout").classList.remove("hidden");
                role_assignment_form.classList.add("hidden");
                //alert(url);
                fetch(url, {
                    headers: {
                        "accept": "application/json",
                    }
                }).then(response => {
                    return response.json();
                }).then(data => {
                    let roles = data.roles;
                    roles.forEach(role => {
                        let checkbox = document.querySelector("#role_" + role);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                    document.querySelector("#loader_layout").classList.add("hidden");
                    role_assignment_form.classList.remove("hidden");
                }).catch(err => {
                    console.log(err);
                })
            });
        });

        const assignUserRole = () => {
            // Validation if a user is selected
            if (role_assignment_form.selected_user_id.value.trim() === "") {
                //alert("Select a user first.");
                Swal.fire({
                    title: "User not selected",
                    text: "Select a user first.",
                    icon: "warning"
                });
                return;
            }

            // Validation if at least one role is selected
            if (role_assignment_form.querySelectorAll("input[type='checkbox']:checked").length === 0) {
                //alert("Select a role.");
                Swal.fire({
                    title: "Role not selected",
                    text: "Select a role.",
                    icon: "warning"
                });
                return;
            }

            /* if (!confirm("Are you sure to assign role(s) to the user?")) {
                return;
            } */
            document.querySelector("#loader_layout").classList.remove("hidden");
            role_assignment_form.classList.add("hidden");

            Swal.fire({
                title: 'Are you sure to assign role(s) to the user?',
                text: '',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, assign',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(role_assignment_form.action, {
                            method: "POST",
                            body: new FormData(role_assignment_form),
                            headers: {
                                "Accept": "application/json", // important for Laravel to return JSON on validation failure
                            }
                        })
                        .then(async (response) => {
                            const data = await response.json();

                            if (!response.ok) {
                                // Laravel validation errors
                                if (data.errors) {
                                    const messages = Object.values(data.errors).flat().join("\n");
                                    alert(messages);
                                } else if (data.message) {
                                    alert(data.message);
                                } else {
                                    alert("Something went wrong.");
                                }
                                //throw new Error("Validation or server error");
                            }

                            // Success
                            const msg = (data.message || "Roles assigned successfully.");
                            Swal.fire({
                                title: "Done!",
                                text: msg,
                                icon: "success"
                            }).then((result) => {
                                document.querySelector("#loader_layout").classList.add(
                                    "hidden");
                                role_assignment_form.classList.remove("hidden");
                            });
                        })
                        .catch((error) => {
                            let msg = "Unexpected error occurred.";
                            console.error("Fetch error:", error);
                            if (typeof error === "string") {
                                //alert(error);
                                msg = error;
                            } else if (error.message) {
                                //alert(error.message);
                                msg = error.message;
                            }
                            Swal.fire({
                                title: "Oops!",
                                text: msg,
                                icon: "error"
                            }).then((result) => {
                                document.querySelector("#loader_layout").classList.add("hidden");
                                role_assignment_form.classList.remove("hidden");
                            });
                        });
                }
            });


        };
    </script>
@endsection
