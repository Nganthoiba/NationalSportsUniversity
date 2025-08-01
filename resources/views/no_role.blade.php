<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Access Restricted</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <!-- Tailwind Play CDN (for quick use; switch to compiled CSS in prod) -->
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
        {{-- 'resources/js/hindiscript.js','resources/js/bengaliscript.js' --}}
    @else
        @vite(['resources/css/tailwind.css', 'resources/css/custom.css'])
    @endif
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 to-white flex items-center justify-center py-12">
    <div class="max-w-4xl w-full mx-4">
        <div class="bg-white shadow-lg rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-2">
            <div class="p-8 flex flex-col justify-center gap-6">
                <h1 class="text-3xl font-semibold text-gray-800">Access Restricted</h1>
                <p class="text-gray-600">
                    Your account does not have any <span class="font-medium text-red-600">role assigned</span> yet.
                    Until an administrator assigns you a valid role, you won’t be able to access the application.
                </p>

                <div class="flex flex-wrap gap-3">
                    <div
                        class="inline-flex items-center bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
                        Pending Role Assignment
                    </div>
                </div>

                <div class="mt-2">
                    <p class="font-medium text-gray-700 mb-2">What you can do:</p>
                    <ul class="list-disc list-inside space-y-1 text-gray-600">
                        <li>Contact your administrator and request a role assignment.</li>
                        <li>If you think this is an error, <a href="{{ route('logout') }}"
                                class="text-indigo-600 underline">logout</a> and login again.</li>
                    </ul>
                </div>

                <div class="flex flex-wrap gap-3 mt-4">
                    <a href="mailto:{{ config('app.admin_email', 'admin@example.com') }}?subject=Role%20Assignment%20Request"
                        class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                        Contact Admin
                    </a>
                    <a href="{{ route('logout') }}"
                        class="inline-block px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Logout
                    </a>
                </div>

                @if (session('error'))
                    <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <p class="text-yellow-700">{{ session('error') }}</p>
                    </div>
                @endif

                <p class="text-sm text-gray-500 mt-4">
                    If you are an administrator, go to the <a href="{{ route('dashboard') }}"
                        class="text-indigo-600 underline">Admin Dashboard</a> to assign roles.
                </p>
            </div>
            <div class="bg-indigo-50 flex items-center justify-center p-8">
                <!-- Simple illustrative SVG -->
                <div class="max-w-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-auto" viewBox="0 0 500 500" fill="none">
                        <rect width="500" height="500" rx="50" fill="#EEF2FF" />
                        <path
                            d="M250 140c-61.856 0-112 50.144-112 112s50.144 112 112 112 112-50.144 112-112-50.144-112-112-112zm0 192c-44.112 0-80-35.888-80-80s35.888-80 80-80 80 35.888 80 80-35.888 80-80 80zm-40-224h80v24h-80v-24zm0 240h80v24h-80v-24z"
                            fill="#6366F1" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="text-center mt-6 text-sm text-gray-400">
            Need help? <a href="mailto:{{ config('app.support_email', 'support@example.com') }}"
                class="underline text-indigo-600">Contact Support</a>
        </div>
    </div>
</body>

</html>
