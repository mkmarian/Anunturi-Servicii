<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — MeseriiRo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

<div class="min-h-screen flex">

    {{-- ── Sidebar ─────────────────────────────────────────── --}}
    <aside class="w-64 bg-gray-900 text-gray-300 flex flex-col flex-shrink-0">
        {{-- Logo --}}
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-6 py-5 border-b border-gray-700">
            <span class="text-xl font-bold"><span class="text-indigo-400">Meserii</span><span class="text-white">Ro</span></span>
            <span class="text-xs bg-indigo-600 text-white px-1.5 py-0.5 rounded font-medium">Admin</span>
        </a>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            @php
                $current = request()->route()->getName();
                function adminActive($route) {
                    return str_starts_with(request()->route()->getName() ?? '', $route)
                        ? 'bg-gray-700 text-white'
                        : 'hover:bg-gray-800 hover:text-white';
                }
            @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ adminActive('admin.dashboard') }}">
                <span>📊</span> Dashboard
            </a>

            <div class="pt-3 pb-1 px-3 text-xs text-gray-500 uppercase tracking-wider">Conținut</div>

            <a href="{{ route('admin.listings.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ adminActive('admin.listings') }}">
                <span>📋</span> Anunțuri
            </a>

            <a href="{{ route('admin.requests.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ adminActive('admin.requests') }}">
                <span>📩</span> Cereri servicii
            </a>

            <div class="pt-3 pb-1 px-3 text-xs text-gray-500 uppercase tracking-wider">Conturi</div>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg {{ adminActive('admin.users') }}">
                <span>👥</span> Utilizatori
            </a>

            <div class="pt-3 pb-1 px-3 text-xs text-gray-500 uppercase tracking-wider">Site</div>

            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-800 hover:text-white">
                <span>🌐</span> Vezi site-ul
            </a>
        </nav>

        {{-- User info --}}
        <div class="px-4 py-4 border-t border-gray-700 text-xs">
            <p class="text-white font-medium">{{ auth()->user()->name }}</p>
            <p class="text-gray-500 capitalize">{{ auth()->user()->role }}</p>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button class="text-red-400 hover:text-red-300">← Deconectare</button>
            </form>
        </div>
    </aside>

    {{-- ── Conținut principal ──────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top bar --}}
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
            <p class="text-sm text-gray-500">{{ now()->format('d.m.Y') }}</p>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm mb-4">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm mb-4">
                    ❌ {{ session('error') }}
                </div>
            @endif
        </div>

        {{-- Page content --}}
        <main class="flex-1 px-6 py-4">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
