<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GovLog Sentinel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-100 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <!-- Hero Section with Logo -->
            <div class="mb-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="text-4xl">🛡️</div>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-purple-700">GovLog Sentinel</h1>
                <p class="text-gray-600 text-sm mt-2">AICTE Compliant Log Management</p>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white dark:bg-gray-800 shadow-xl overflow-hidden sm:rounded-2xl border-t-4 border-purple-600">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-600">
                <p>&copy; 2026 GovLog Sentinel. All rights reserved.</p>
            </div>
        </div>
    </body>
</html>
