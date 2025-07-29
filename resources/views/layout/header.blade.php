<header class="w-full text-sm not-has-[nav]:hidden py-2">
    {{-- max-w-[335px] lg:max-w-7xl --}}
    @if (Route::has('login'))
        <nav class="flex items-center justify-between px-4 py-2 bg-white shadow-md">
            <!-- Logo & Title -->
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/National_Sports_University_logo.png') }}" alt="Logo" class="h-16 w-auto">
                <div>
                    <h3 class="text-lg sm:text-2xl font-bold">{{ env('APP_TITLE') }}</h3>
                    <h5 class="hidden sm:block text-sm text-gray-600">
                        Department of Sports, Ministry of Youth Affairs & Sports, Government of India
                    </h5>
                </div>
            </div>

            <!-- Desktop Links -->

            @if (Auth::check())
                <div class="relative inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <!-- Profile dropdown -->
                    @php
                        $currentRole = session('currentRole');
                        $roles = Auth::user()->getRoles();
                    @endphp

                    <div class="roles text-bold text-xs">
                        {{-- {{ implode(',', Auth::user()->getAssignedRoles()) }} --}}
                        <select name="roleOption" id="roleOption"
                            class="cursor-pointer border border-gray-400 rounded-sm p-2" title="Switch User Role">
                            <option value="">Switch Role</option>
                            @foreach ($roles as $role)
                                @php
                                    $selected = $currentRole ? ($currentRole->id == $role->id ? 'selected' : '') : '';
                                @endphp
                                <option value="{{ $role->id }}" {{ $selected }}>{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative ml-3">
                        <div class="flex gap-2 text-end">
                            <button type="button"
                                class="relative flex rounded-full bg-gray-800 text-sm 
                                focus:ring-2 focus:ring-white focus:ring-offset-2
                                 focus:ring-offset-gray-800 focus:outline-hidden cursor-pointer"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">Open user menu</span>
                                <img class="size-8 rounded-full"
                                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                    alt="">
                            </button>
                        </div>


                        <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 ring-1 shadow-lg ring-black/5 focus:outline-hidden hidden"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                            tabindex="-1">
                            <a href="javascript:void(0);" class="block px-4 py-2">
                                <div
                                    style="padding:3px; background: #ccc; border-radius:5px; border: 1px solid #CCC; font-size: 0.9rem;">
                                    <strong>{{ Auth::user()->full_name }}</strong>
                                    <h6 class="text-email">{{ Auth::user()->email }}</h6>
                                </div>
                                <div class="w-full border-t mt-3"></div>
                            </a>
                            <!-- Active: "bg-gray-100 outline-hidden", Not Active: "" -->


                            <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-700"
                                role="menuitem" tabindex="-1" id="user-menu-item-1"><i class="fas fa-cog"></i>
                                Settings</a>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700"
                                role="menuitem" tabindex="-1" id="user-menu-item-2"><i
                                    class="fa-solid fa-right-from-bracket"></i>
                                Sign out</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="hidden sm:flex gap-4">
                    <a href="{{ url('/') }}"
                        class="px-5 py-1.5 text-sm leading-normal text-[#1b1b18] border 
                        rounded-sm hover:border-gray-400 dark:text-[#EDEDEC] 
                        dark:border-[#3E3E3A] dark:hover:border-gray-500 {{ request()->routeIs('landing') ? 'bg-blue-600 text-white border-blue-300' : 'border-gray-300' }}">
                        Home
                    </a>
                    <a href="{{ route('login') }}"
                        class="px-5 py-1.5 text-sm leading-normal text-[#1b1b18] border 
                        rounded-sm hover:border-gray-400 dark:text-[#EDEDEC] 
                        dark:border-[#3E3E3A] dark:hover:border-gray-500 {{ request()->routeIs('login') ? 'bg-blue-600 text-white border-blue-300' : 'border-gray-300' }}">
                        Log in
                    </a>
                    {{-- 
                    <a href="{{ route('register') }}"
                        class="px-5 py-1.5 text-sm leading-normal text-[#1b1b18] border 
                        rounded-sm hover:border-gray-400 dark:text-[#EDEDEC] 
                        dark:border-[#3E3E3A] dark:hover:border-gray-500 {{ request()->routeIs('register') ? 'bg-blue-600 text-white border-blue-300' : 'border-gray-300' }}">
                        Register
                    </a> --}}

                </div>
            @endif

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button"
                class="sm:hidden p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer">
                <svg class="w-6 h-6 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12h18M3 6h18M3 18h18"></path>
                </svg>
            </button>
        </nav>

        @if (Auth::check())
            <!-- Menu Bar -->
            <div class="bg-gray-500 text-gray-300">
                <div class="max-w-7xl mx-auto px-4">

                    <ul class="hidden sm:flex justify-left space-x-6 py-2">
                        @php
                            $menus = App\CustomLibrary\Menu::getPermittedMenus(
                                $currentRole->id /*Auth::user()->getRoles()*/,
                            );
                        @endphp
                        @foreach ($menus as $menu)
                            @if (empty($menu['sub_menus']))
                                <li>
                                    <a href="{{ $menu['link'] ?? '#' }}"
                                        class="{{ request()->routeIs($menu['route']) ? 'text-white font-bold' : 'hover:text-gray-100' }}">{{ $menu['menu_label'] }}</a>

                                </li>
                            @else
                                <li class="relative group">
                                    <a href="javascript:void(0);"
                                        class="inline-block relative {{ request()->routeIs('courses.*') ? 'text-white font-bold' : 'hover:text-gray-100' }}">
                                        {{ $menu['menu_label'] }}
                                    </a>
                                    <!-- Dropdown Menu -->
                                    <ul
                                        class="absolute left-0 mt-2 w-48 bg-white text-gray-700 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">

                                        @foreach ($menu['sub_menus'] as $submenu)
                                            <li>
                                                <a href="{{ $submenu['link'] ?? '#' }}"
                                                    class="block px-4 py-2 hover:bg-gray-100">
                                                    {{ $submenu['menu_label'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>

        @endif

        <!-- Mobile Menu (Hidden by Default) -->
        <div id="mobile-menu" class="hidden sm:hidden bg-white border-t border-gray-200">
            <div class="flex flex-col items-center gap-3 py-4">
                @auth
                    <div class="w-3/4 border-t mt-3"></div>

                    <a href="{{ route('dashboard') }}"
                        class="w-3/4 text-center px-5 py-2 text-sm text-gray-700 hover:bg-gray-200">Home</a>
                    <a href="{{ route('displayStudents') }}"
                        class="w-3/4 text-center px-5 py-2 text-sm text-gray-700 hover:bg-gray-200">Student List</a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-3/4 text-center px-5 py-2 text-sm text-[#1b1b18] border border-gray-300 rounded-sm hover:border-gray-400 dark:text-[#EDEDEC] dark:border-[#3E3E3A] dark:hover:border-gray-500">
                        Log in
                    </a>
                    {{-- 
                    <a href="{{ route('register') }}"
                        class="w-3/4 text-center px-5 py-2 text-sm text-[#1b1b18] border border-gray-300 rounded-sm hover:border-gray-400 dark:text-[#EDEDEC] dark:border-[#3E3E3A] dark:hover:border-gray-500">
                        Register
                    </a> --}}
                @endauth
            </div>
        </div>

        <!-- JavaScript to Toggle Mobile Menu -->
        <script>
            const mobileMenuButton = document.getElementById("mobile-menu-button");
            const mobileMenu = document.getElementById("mobile-menu");

            mobileMenuButton.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
            });
        </script>

        @if (Auth::check())
            <script>
                const userMenuButton = document.getElementById("user-menu-button");
                const userMenu = document.querySelector("[aria-labelledby='user-menu-button']");

                userMenuButton.addEventListener("click", function() {
                    userMenu.classList.toggle("hidden");
                });

                // Optional: Close dropdown when clicking outside
                document.addEventListener("click", function(event) {
                    if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                        userMenu.classList.add("hidden");
                    }
                });

                var roleOption = document.querySelector("#roleOption");
                if (roleOption) {
                    roleOption.addEventListener("change", (event) => {
                        var url = "{{ route('switchRole', ':roleId') }}";
                        if (roleOption.value.trim() == "") {
                            return;
                        }
                        url = url.replace(":roleId", roleOption.value);
                        window.location.href = url;
                    });
                }
            </script>
        @endif
    @endif
</header>
