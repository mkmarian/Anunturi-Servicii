<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MeseriiRo — Găsește meșteșugari și servicii în România</title>
    <meta name="description" content="Platformă de anunțuri pentru meșteșugari și servicii. Electricieni, constructori, instalatori și alți specialiști din toată România.">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">

@include('layouts.navigation')

{{-- ── HERO ─────────────────────────────────────────────────────────── --}}
<section class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-indigo-500 text-white py-16 sm:py-24">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">
            Găsești meșteșugarul potrivit,<br class="hidden sm:block"> oriunde în România
        </h1>
        <p class="mt-4 text-indigo-100 text-lg max-w-2xl mx-auto">
            Electricieni, constructori, instalatori, zugravi și zeci de alți specialiști.
            {{ number_format($stats['listings']) }} anunțuri active în {{ $stats['counties'] }} județe.
        </p>

        {{-- Search hero --}}
        <form action="{{ route('listings.index') }}" method="GET"
              class="mt-8 flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto">
            <input type="text" name="q" placeholder="Ce serviciu cauți? (ex: electrician, instalator...)"
                   value="{{ request('q') }}"
                   class="flex-1 rounded-xl px-5 py-3 text-gray-900 text-base focus:outline-none focus:ring-2 focus:ring-white shadow-lg" />
            <select name="county"
                    class="sm:w-44 rounded-xl px-4 py-3 text-gray-700 text-base focus:outline-none focus:ring-2 focus:ring-white shadow-lg">
                <option value="">Toate județele</option>
                @foreach($counties as $county)
                    <option value="{{ $county->id }}">{{ $county->name }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-6 py-3 bg-white text-indigo-700 font-semibold rounded-xl hover:bg-indigo-50 shadow-lg transition">
                Caută
            </button>
        </form>
    </div>
</section>

{{-- ── CATEGORII ────────────────────────────────────────────────────── --}}
<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Categorii populare</h2>
        <p class="text-gray-500 mb-8">Alege domeniul de care ai nevoie</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('listings.index', ['category' => $cat->id]) }}"
                   class="group flex flex-col items-center p-5 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:shadow-md transition text-center border border-transparent hover:border-indigo-200">
                    <span class="text-3xl mb-2">{{ $cat->icon }}</span>
                    <span class="text-sm font-medium text-gray-800 group-hover:text-indigo-700 leading-snug">
                        {{ $cat->name }}
                    </span>
                    @if($cat->listings_count > 0)
                        <span class="mt-1 text-xs text-gray-400">{{ $cat->listings_count }} anunț(uri)</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── ANUNTURI RECENTE ─────────────────────────────────────────────── --}}
@if($recentListings->isNotEmpty())
<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Anunțuri recente</h2>
                <p class="text-gray-500 mt-1">Meșteșugari disponibili chiar acum</p>
            </div>
            <a href="{{ route('listings.index') }}" class="text-indigo-600 hover:underline text-sm font-medium">
                Vezi toate →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($recentListings as $listing)
                <a href="{{ route('listings.show', $listing->slug) }}"
                   class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden group">
                    {{-- Imagine sau placeholder --}}
                    @if($listing->images->isNotEmpty())
                        <div class="h-44 overflow-hidden">
                            <img src="{{ Storage::url($listing->images->first()->path) }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                 alt="{{ $listing->title }}">
                        </div>
                    @else
                        <div class="h-44 bg-gradient-to-br from-indigo-100 to-indigo-50 flex items-center justify-center text-5xl">
                            {{ $listing->category->icon ?? '🔧' }}
                        </div>
                    @endif

                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="text-xs text-indigo-600 font-medium">{{ $listing->category->name }}</p>
                            @if($listing->featured_until && $listing->featured_until->isFuture())
                                <span class="text-xs bg-amber-100 text-amber-600 px-1.5 py-0.5 rounded font-medium">⭐ Top</span>
                            @endif
                        </div>
                        <h3 class="font-semibold text-gray-900 leading-snug line-clamp-2">{{ $listing->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            📍 {{ $listing->city->name ?? '' }}, {{ $listing->county->name ?? '' }}
                        </p>
                        @if($listing->price)
                            <p class="mt-2 font-bold text-indigo-700">
                                {{ number_format($listing->price, 0, ',', '.') }} RON
                                @if($listing->price_type !== 'fix')
                                    <span class="text-xs font-normal text-gray-400">/ {{ $listing->price_type }}</span>
                                @endif
                            </p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CERERI RECENTE ───────────────────────────────────────────────── --}}
@if($recentRequests->isNotEmpty())
<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Cereri recente</h2>
                <p class="text-gray-500 mt-1">Clienți care caută specialiști</p>
            </div>
            <a href="{{ route('service-requests.index') }}" class="text-indigo-600 hover:underline text-sm font-medium">
                Vezi toate →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($recentRequests as $sr)
                <a href="{{ route('service-requests.show', $sr->slug) }}"
                   class="bg-gray-50 rounded-xl p-5 hover:bg-indigo-50 hover:shadow-md transition border border-gray-100 hover:border-indigo-200">
                    <p class="text-xs text-green-600 font-medium mb-1">{{ $sr->category->name }}</p>
                    <h3 class="font-semibold text-gray-900 leading-snug line-clamp-2">{{ $sr->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">📍 {{ $sr->city->name ?? '' }}, {{ $sr->county->name ?? '' }}</p>
                    <p class="mt-2 text-xs text-gray-400">{{ $sr->responses_count }} oferte primite</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CTA ──────────────────────────────────────────────────────────── --}}
@guest
<section class="py-16 bg-indigo-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold">Ești meșteșugar sau oferi servicii?</h2>
        <p class="mt-3 text-indigo-100 text-lg">
            Înregistrează-te gratuit și postează primul tău anunț în mai puțin de 5 minute.
        </p>
        </div>
    </div>
</section>
@endguest

{{-- ── FOOTER ───────────────────────────────────────────────────────── --}}
<footer class="bg-gray-900 text-gray-400 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-center">
        © {{ date('Y') }} MeseriiRo. Toate drepturile rezervate.
    </div>
</footer>

</body>
</html>
