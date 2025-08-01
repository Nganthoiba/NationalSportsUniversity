<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Oops!' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
        {{-- 'resources/js/hindiscript.js','resources/js/bengaliscript.js' --}}
    @else
        @vite(['resources/css/tailwind.css', 'resources/css/custom.css'])
    @endif
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-xl w-full text-center">
        @php
            $iconMap = [
                'error' => '❌',
                'warning' => '⚠️',
                'info' => 'ℹ️',
                'success' => '✅',
            ];
            $type = $type ?? 'error';
            $icon = $iconMap[$type] ?? '❌';
        @endphp

        <div class="text-5xl mb-4">{{ $icon }}</div>

        <h1 class="text-2xl font-semibold text-gray-800 mb-2">
            {{ $title ?? 'Something went wrong' }}
        </h1>

        <p class="text-gray-600 mb-6 leading-relaxed">
            {{ $message ?? 'An unexpected issue occurred. Please try again later or contact support.' }}
        </p>

        @if (isset($btn_link))
            <a href="{{ $btn_link }}"
                class="cursor-pointer inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                {{ $btn_label }}
            </a>
        @else
            <button onclick="history.back()"
                class="cursor-pointer inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition">
                ← Go Back
            </button>
        @endif
    </div>

</body>

</html>
