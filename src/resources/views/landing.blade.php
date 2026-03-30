<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="text-center max-w-lg">
            <x-application-logo class="w-16 h-16 mx-auto mb-6 fill-current text-gray-500" />

            <h1 class="text-4xl font-bold text-gray-900 mb-3">{{ config('app.name') }}</h1>
            <p class="text-lg text-gray-500 mb-10">Monitor your domains and get notified when they go down.</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-700 transition">
                    Get started
                </a>
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-900 font-semibold rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                    Log in
                </a>
            </div>
        </div>
    </div>
</body>
</html>
