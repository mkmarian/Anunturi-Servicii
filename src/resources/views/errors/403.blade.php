<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Acces interzis · MeseriiRo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col items-center justify-center px-4">

    <a href="{{ route('home') }}" class="text-2xl font-bold mb-10">
        <span class="text-indigo-600">Meserii</span><span class="text-gray-800">Ro</span>
    </a>

    <div class="text-center max-w-md">
        <p class="text-8xl font-extrabold text-red-500">403</p>
        <h1 class="mt-4 text-2xl font-bold text-gray-900">Acces interzis</h1>
        <p class="mt-2 text-gray-500">
            {{ $exception->getMessage() ?: 'Nu ai permisiunea de a accesa această pagină.' }}
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}"
               class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition">
                ← Înapoi acasă
            </a>
            @guest
                <a href="{{ route('login') }}"
                   class="px-6 py-3 border border-gray-200 text-gray-700 rounded-xl font-medium hover:bg-gray-50 transition">
                    Autentifică-te
                </a>
            @endguest
        </div>
    </div>

    <p class="mt-16 text-xs text-gray-300">© {{ date('Y') }} MeseriiRo</p>
</body>
</html>
