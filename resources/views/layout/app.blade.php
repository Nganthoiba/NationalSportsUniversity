<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Institution</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
        {{-- 'resources/js/hindiscript.js','resources/js/bengaliscript.js' --}}
    @else
        @vite(['resources/css/tailwind.css', 'resources/css/custom.css'])
    @endif
    @yield('css')
    @yield('head_javascripts')
</head>

<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex items-center lg:justify-center min-h-screen flex-col">

    @section('header')
        @include('layout.header') <!-- Include the header -->
    @show
    {{--  items-center justify-center --}}
    <div class="flex w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        {{-- max-w-[335px] lg:max-w-4xl --}}
        <main class="w-full flex-col-reverse lg:flex-row px-2 py-1 lg:p-3">
            @yield('content') <!-- Placeholder for the main content -->
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif

    @yield('javascripts') <!-- Placeholder for additional scripts -->

</body>

</html>
