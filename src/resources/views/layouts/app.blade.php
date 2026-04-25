<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($seoTitle) ? $seoTitle . ' — ' : (isset($title) ? $title . ' — ' : '') }}{{ config('app.name', 'MeseriiRo') }}</title>

        {{-- SEO meta tags --}}
        @isset($seoDescription)
            <meta name="description" content="{{ $seoDescription }}">
        @endisset

        {{-- Open Graph --}}
        <meta property="og:site_name" content="{{ config('app.name', 'MeseriiRo') }}">
        <meta property="og:type" content="{{ $ogType ?? 'website' }}">
        <meta property="og:title" content="{{ $seoTitle ?? config('app.name', 'MeseriiRo') }}">
        @isset($seoDescription)
            <meta property="og:description" content="{{ $seoDescription }}">
        @endisset
        @isset($ogImage)
            <meta property="og:image" content="{{ $ogImage }}">
        @endisset
        <meta property="og:url" content="{{ request()->fullUrl() }}">

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="summary_large_image">
        @isset($seoTitle)
            <meta name="twitter:title" content="{{ $seoTitle }}">
        @endisset
        @isset($seoDescription)
            <meta name="twitter:description" content="{{ $seoDescription }}">
        @endisset
        @isset($ogImage)
            <meta name="twitter:image" content="{{ $ogImage }}">
        @endisset

        {{-- Canonical URL --}}
        @isset($canonical)
            <link rel="canonical" href="{{ $canonical }}">
        @endisset

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
