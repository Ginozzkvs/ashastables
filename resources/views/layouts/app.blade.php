<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@100..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-dark text-white">
    <div x-data="{ sidebarExpanded: localStorage.getItem('sidebarExpanded') === 'true' }" 
         @sidebar-toggled.window="sidebarExpanded = $event.detail"
         class="min-h-screen transition-all duration-300"
         :class="sidebarExpanded ? 'lg:pl-64' : 'lg:pl-20'">

        {{-- ✅ KEEP THIS --}}
        @include('layouts.navigation')

        <!-- Add padding for mobile header -->
        <div class="lg:hidden h-16"></div>

        @isset($header)
            <header class="bg-dark-card border-b border-gold">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="py-10 bg-dark">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>


    </div>
</body>
</html>
