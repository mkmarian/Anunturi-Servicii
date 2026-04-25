<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>503 — În mentenanță · MeseriiRo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-indigo-700 min-h-screen flex flex-col items-center justify-center px-4 text-white">

    <div class="text-center max-w-md">
        <p class="text-7xl mb-4">🔧</p>
        <p class="text-2xl font-bold mb-1">
            <span class="text-indigo-200">Meserii</span>Ro
        </p>
        <h1 class="mt-4 text-3xl font-bold">În mentenanță</h1>
        <p class="mt-3 text-indigo-200 text-lg">
            Lucrăm la îmbunătățiri. Revenim în curând!
        </p>
        @if(isset($exception) && $exception->getMessage())
            <p class="mt-4 text-indigo-300 text-sm">{{ $exception->getMessage() }}</p>
        @endif
    </div>

    <p class="mt-16 text-xs text-indigo-400">© {{ date('Y') }} MeseriiRo</p>
</body>
</html>
