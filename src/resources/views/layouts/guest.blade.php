<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'MeseriiRo') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center bg-gradient-to-br from-indigo-50 via-white to-violet-50 px-4 py-10">

            {{-- Logo --}}
            <a href="/" class="mb-8 flex items-center gap-2">
                <span class="text-2xl font-extrabold text-indigo-600">Meserii<span class="text-gray-800">Ro</span></span>
            </a>

            {{-- Card --}}
            <div class="w-full max-w-md bg-white shadow-xl rounded-2xl px-8 py-8 border border-gray-100">
                {{ $slot }}
            </div>

            {{-- Back link --}}
            <a href="{{ route('home') }}" class="mt-6 text-sm text-gray-400 hover:text-gray-600 transition">
                ← Înapoi pe site
            </a>
        </div>
    </body>
</html>
