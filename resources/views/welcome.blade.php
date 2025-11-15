<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="bg-white">
            @if (Route::has('login'))
                <nav class="flex justify-between items-center p-6">
                    <div>
                        <h1 class="text-2xl font-bold">CBT Laravel</h1>
                    </div>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Register</a>
                            @endif
                        @endauth
                    </div>
                </nav>
            @endif

            <main class="min-h-screen flex flex-col items-center justify-center p-6">
                <div class="text-center max-w-2xl">
                    <h2 class="text-4xl font-bold mb-4">Welcome to CBT Laravel</h2>
                    <p class="text-gray-600 mb-8">
                        A comprehensive Computer Based Test system built with Laravel.
                    </p>
                    
                    @if (Route::has('login'))
                        @auth
                            <div class="space-y-2">
                                <a href="{{ url('/dashboard') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Go to Dashboard
                                </a>
                            </div>
                        @else
                            <div class="space-y-4">
                                <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mr-4">
                                    Log In
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        Register
                                    </a>
                                @endif
                            </div>
                        @endauth
                    @endif
                </div>
            </main>
        </div>
    </body>
</html>
