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
<section class="relative bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600 text-white overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3 blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4 blur-3xl pointer-events-none"></div>

    <div class="relative max-w-4xl mx-auto px-4 py-20 sm:py-28 text-center">
        <span class="inline-block mb-4 px-3 py-1 bg-white/15 rounded-full text-sm font-medium tracking-wide">
            🇷🇴 Platforma #1 de servicii din România
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight">
            Găsești meșteșugarul<br class="hidden sm:block"> potrivit, oriunde
        </h1>
        <p class="mt-5 text-indigo-100 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">
            Electricieni, constructori, instalatori, zugravi și zeci de alți specialiști.
        </p>

        {{-- Stats badges --}}
        <div class="flex flex-wrap justify-center gap-4 mt-6 mb-8 text-sm">
            <span class="flex items-center gap-1.5 bg-white/15 px-3 py-1.5 rounded-full">
                📋 <strong>{{ number_format($stats['listings']) }}</strong> anunțuri active
            </span>
            <span class="flex items-center gap-1.5 bg-white/15 px-3 py-1.5 rounded-full">
                📍 <strong>{{ $stats['counties'] }}</strong> județe
            </span>
            <span class="flex items-center gap-1.5 bg-white/15 px-3 py-1.5 rounded-full">
                ⭐ Servicii verificate
            </span>
        </div>

        {{-- Search hero --}}
        <form action="{{ route('listings.index') }}" method="GET"
              class="flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto">
            <input type="text" name="q" placeholder="Ce serviciu cauți? (ex: electrician...)"
                   value="{{ request('q') }}"
                   class="flex-1 rounded-xl px-5 py-3.5 text-gray-900 text-base focus:outline-none focus:ring-2 focus:ring-white/60 shadow-xl" />
            <select name="county"
                    class="sm:w-44 rounded-xl px-4 py-3.5 text-gray-700 text-base focus:outline-none focus:ring-2 focus:ring-white/60 shadow-xl">
                <option value="">Toate județele</option>
                @foreach($counties as $county)
                    <option value="{{ $county->id }}">{{ $county->name }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-7 py-3.5 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 shadow-xl transition whitespace-nowrap">
                Caută
            </button>
        </form>
    </div>
</section>

{{-- ── CATEGORII ────────────────────────────────────────────────────── --}}
<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Categorii</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            <a href="{{ route('listings.index') }}"
               class="group flex flex-col items-center p-5 bg-gray-50 rounded-2xl hover:bg-indigo-600 hover:shadow-lg transition-all duration-200 text-center border border-gray-100 hover:border-indigo-600">
                <span class="text-3xl mb-2.5">🗂️</span>
                <span class="text-sm font-medium text-gray-700 group-hover:text-white leading-snug">
                    Toate categoriile
                </span>
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('listings.index', ['category' => $cat->id]) }}"
                   class="group flex flex-col items-center p-5 bg-gray-50 rounded-2xl hover:bg-indigo-600 hover:shadow-lg transition-all duration-200 text-center border border-gray-100 hover:border-indigo-600">
                    <span class="text-3xl mb-2.5">{{ $cat->icon }}</span>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-white leading-snug">
                        {{ $cat->name }}
                    </span>
                    @if($cat->listings_count > 0)
                        <span class="mt-1.5 text-xs text-gray-400 group-hover:text-indigo-200">{{ $cat->listings_count }} anunțuri</span>
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
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Anunțuri recente</h2>
                <p class="text-gray-500 mt-1 text-sm">Meșteșugari disponibili chiar acum</p>
            </div>
            <a href="{{ route('listings.index') }}" class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 text-sm font-semibold transition">
                Vezi toate <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($recentListings as $listing)
                <div class="relative bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group border border-gray-100 hover:border-indigo-200">
                    <a href="{{ route('listings.show', $listing->slug) }}" class="block">
                        @if($listing->images->isNotEmpty())
                            <div class="h-44 overflow-hidden">
                                <img src="{{ Storage::url($listing->images->first()->path) }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     alt="{{ $listing->title }}">
                            </div>
                        @else
                            <div class="h-44 bg-gradient-to-br from-indigo-50 to-violet-50 flex items-center justify-center text-5xl">
                                {{ $listing->category->icon ?? '🔧' }}
                            </div>
                        @endif

                        <div class="p-4">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded-full">{{ $listing->category->name }}</span>
                                @if($listing->featured_until && $listing->featured_until->isFuture())
                                    <span class="text-xs bg-amber-50 text-amber-600 px-2 py-0.5 rounded-full font-semibold">⭐ Top</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-900 leading-snug line-clamp-2 text-sm">{{ $listing->title }}</h3>
                            <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                {{ $listing->city->name ?? '' }}, {{ $listing->county->name ?? '' }}
                            </p>
                            @if($listing->price)
                                <p class="mt-2.5 font-bold text-indigo-700 text-sm">
                                    {{ number_format($listing->price, 0, ',', '.') }} RON
                                    @if($listing->price_type !== 'fix')
                                        <span class="text-xs font-normal text-gray-400">/ {{ $listing->price_type }}</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                    </a>
                    @auth
                    <button onclick="toggleFav(event, this, {{ $listing->id }})"
                            class="absolute top-2 right-2 z-10 w-8 h-8 flex items-center justify-center bg-white/80 hover:bg-white rounded-full shadow transition"
                            title="{{ in_array($listing->id, $favoriteIds) ? 'Elimină din favorite' : 'Salvează la favorite' }}">
                        <svg class="w-4 h-4 {{ in_array($listing->id, $favoriteIds) ? 'text-red-500' : 'text-gray-300' }}" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </button>
                    @endauth
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CERERI RECENTE ───────────────────────────────────────────────── --}}
@if($recentRequests->isNotEmpty())
<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Cereri recente</h2>
                <p class="text-gray-500 mt-1 text-sm">Clienți care caută specialiști</p>
            </div>
            <a href="{{ route('service-requests.index') }}" class="flex items-center gap-1 text-indigo-600 hover:text-indigo-800 text-sm font-semibold transition">
                Vezi toate <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($recentRequests as $sr)
                <a href="{{ route('service-requests.show', $sr->slug) }}"
                   class="group bg-gray-50 rounded-2xl p-5 hover:bg-indigo-600 hover:shadow-lg transition-all duration-200 border border-gray-100 hover:border-indigo-600">
                    <span class="inline-block text-xs text-green-700 font-semibold bg-green-50 group-hover:bg-white/20 group-hover:text-white px-2.5 py-0.5 rounded-full mb-2 transition-colors">{{ $sr->category->name }}</span>
                    <h3 class="font-semibold text-gray-900 group-hover:text-white leading-snug line-clamp-2 text-sm transition-colors">{{ $sr->title }}</h3>
                    <p class="text-xs text-gray-400 group-hover:text-indigo-200 mt-2 flex items-center gap-1 transition-colors">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        {{ $sr->city->name ?? '' }}, {{ $sr->county->name ?? '' }}
                    </p>
                    <p class="mt-2 text-xs text-gray-400 group-hover:text-indigo-200 transition-colors">{{ $sr->responses_count }} oferte primite</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CUM FUNCTIONEAZA ─────────────────────────────────────────────── --}}
<section class="py-16 bg-gray-50 border-t border-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Cum funcționează?</h2>
        <p class="text-gray-500 mb-12 text-sm">Simplu, rapid și gratuit</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-2xl mb-4">🔍</div>
                <h3 class="font-semibold text-gray-900 mb-1">1. Caută</h3>
                <p class="text-sm text-gray-500">Găsești meșteșugari după categorie, județ sau cuvânt cheie.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-2xl mb-4">💬</div>
                <h3 class="font-semibold text-gray-900 mb-1">2. Contactează</h3>
                <p class="text-sm text-gray-500">Trimite un mesaj direct sau postează o cerere și primești oferte.</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center text-2xl mb-4">✅</div>
                <h3 class="font-semibold text-gray-900 mb-1">3. Angajează</h3>
                <p class="text-sm text-gray-500">Alegi meșteșugarul potrivit și lași o recenzie după finalizare.</p>
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ──────────────────────────────────────────────────────────── --}}
@guest
<section class="py-16 bg-gradient-to-r from-indigo-600 to-violet-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold">Ești meșteșugar sau oferi servicii?</h2>
        <p class="mt-3 text-indigo-100 text-lg max-w-xl mx-auto">
            Înregistrează-te gratuit și postează primul tău anunț în mai puțin de 5 minute.
        </p>
        <div class="mt-8 flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}"
               class="px-8 py-3.5 bg-white text-indigo-700 font-bold rounded-xl hover:bg-indigo-50 shadow-lg transition">
                Înregistrare gratuită
            </a>
            <a href="{{ route('listings.index') }}"
               class="px-8 py-3.5 bg-white/15 text-white font-bold rounded-xl hover:bg-white/25 border border-white/30 transition">
                Caută servicii
            </a>
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

<script>
function toggleFav(event, btn, listingId) {
    event.preventDefault();
    fetch('/favorite/' + listingId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const svg = btn.querySelector('svg');
        if (data.favorited) {
            svg.classList.add('text-red-500');
            svg.classList.remove('text-gray-300');
            btn.title = 'Elimină din favorite';
        } else {
            svg.classList.remove('text-red-500');
            svg.classList.add('text-gray-300');
            btn.title = 'Salvează la favorite';
        }
    });
}
</script>
</body>
</html>
