@extends('layout.app')
@section('css')
    <style>
        .grid-container {
            display: grid;
            /* Enables CSS Grid for the ul element */
            grid-template-columns: repeat(3, 1fr);
            /* Creates 3 equal-width columns */
            gap: 10px;
            /* Adds a 10px gap between grid items */
        }

        .grid-container li {
            /* Optional: Add styling to the list items themselves */
            background-color: rgb(241, 242, 243);
            text-align: left;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="w-1/2 mx-auto">
            @include('layout.server_response')
        </div>
        <h4 class="border-b border-gray-300 p-2 font-bold text-color-600 text-2xl mb-3">Assign menus to role</h4>
        <form name="createMenuRoleMapForm" action="{{ route('menu.createMenuRoleMap') }}" method="POST">
            @csrf
            <div class="flex">
                <div class="bg-white shadow-lg p-3 w-1/3 mr-2">
                    <h3 class="text-gray-600 border-b border-gray-300 mb-2">Select a role:</h3>
                    @foreach ($roles as $role)
                        <div class="p-2 m-2 border rounded-sm border-gray-400">
                            @php
                                $roleSelected = old('role_id', '') == $role->id ? 'checked' : '';
                            @endphp
                            <input type="radio" name="role_id" value="{{ $role->id }}" id="role_{{ $role->id }}"
                                class="role-btn" data-allowed-menus="{{ json_encode($role->allowed_menus) }}"
                                {{ $roleSelected }}>
                            <label for="role_{{ $role->id }}"
                                class="font-bold text-gray-600 cursor-pointer">{{ $role->role_name }}</label>
                            <p class="text-xs">{{ $role->role_description }}</p>
                        </div>
                    @endforeach
                    <div class="mt-2">
                        @if ($errors->has('role_id'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('role_id') }}</span>
                        @endif
                    </div>
                </div>
                <div class="bg-white shadow-lg p-3 w-2/3 ml-2">
                    <h3 class="text-gray-600 border-b border-gray-300 mb-2">Select menus that will be assigned to the
                        selected
                        role:
                    </h3>
                    <ul class="mt-2 grid-container">
                        @foreach ($menus as $menu)
                            <li class="border border-gray-400 rounded-sm p-2 m-2">
                                @php
                                    $oldMenus = old('allowed_menus', []);
                                    $checked = in_array($menu['menu_name'], $oldMenus) ? 'checked' : '';
                                @endphp
                                <div>
                                    <input type="checkbox" class="allowed_menus_checkbox" name="allowed_menus[]"
                                        value="{{ $menu['menu_name'] }}" id="menu_{{ $menu['menu_name'] }}"
                                        {{ $checked }}>
                                    <label for="menu_{{ $menu['menu_name'] }}"
                                        class="cursor-pointer">{{ $menu['menu_label'] }}</label>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-2">
                        @if ($errors->has('allowed_menus'))
                            <span class="text-red-500 text-sm mt-1">{{ $errors->first('allowed_menus') }}</span>
                        @endif
                    </div>
                    <div class="mt-2 p-2">
                        <div><button class="btn btn-success">Assign Menus</button></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('javascripts')
    <script>
        var role_inputs = document.querySelectorAll("input[name='role_id']");
        role_inputs.forEach(roleElement => {
            roleElement.addEventListener("click", (e) => {

                //refreshing the check elements
                document.querySelectorAll("input[type='checkbox'].allowed_menus_checkbox:checked").forEach(
                    element => {
                        element.checked = false;
                    });

                var allowed_menus = roleElement.getAttribute("data-allowed-menus");
                if (allowed_menus.trim() != "") {
                    try {
                        var menus = JSON.parse(allowed_menus);
                        menus.forEach((menu_name) => {
                            var menuCheckbox = document.getElementById("menu_" + menu_name);
                            if (menuCheckbox) {
                                menuCheckbox.checked = true;
                            }
                        });
                    } catch (error) {
                        console.error(error)
                    }
                }
            })
        });

        document.forms['createMenuRoleMapForm'].onsubmit = function(event) {
            event.preventDefault();
            if (!confirm("Have you confirmed to assign those selected menus to the selected role?")) {
                return false;
            }
            this.submit();
        };
    </script>
@endsection
