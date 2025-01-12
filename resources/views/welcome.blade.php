<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Add fallback CSS or JS here -->
    @endif
</head>
<body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-white to-blue-100 text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="flex items-center justify-between py-6 px-8 bg-white shadow-sm">
            <div class="flex items-center">
                <img src="{{ asset('images/mc.png') }}" alt="MC PRODUCT Logo" class="h-12 w-12">
                <span class="ml-3 text-2xl font-semibold text-gray-800">MC Product</span>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col items-center justify-center text-center px-6">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to MC Product</h1>
            <p class="text-lg text-gray-600 mb-8">Build amazing web applications with Latest Technology.</p>
            <div class="space-x-4">
                <a href="{{ route('register') }}" class="px-6 py-3 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition">Get Started</a>
                <a href="{{ route('login') }}" class="px-6 py-3 border border-blue-500 text-blue-500 rounded-lg shadow-md hover:bg-blue-100 transition">Log In</a>
            </div>
        </main>

        <!-- Footer -->
        <footer class="py-4 bg-gray-100 text-gray-500 text-center">
            Created by Nilanga - 0770714587 &copy; {{ date('Y') }}
        </footer>
    </div>
</body>
</html>
