<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Titlu --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Anunțuri servicii</h1>
                <p class="text-sm text-gray-500 mt-1">Găsește meșteșugari și specialiști din toată România</p>
            </div>

            {{-- Filtru --}}
            <form method="GET" class="bg-white shadow-sm rounded-2xl p-5 mb-6 border border-gray-100">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Caută</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="meserias, serviciu..."
                               class="block w-full border-gray-200 rounded-xl shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5" />
                    </div>

                    <div class="min-w-[150px]">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Categorie</label>
                        <select name="category"
                                class="block w-full border-gray-200 rounded-xl shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                            <option value="">Toate</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="min-w-[150px]">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Județ</label>
                        <select name="county"
                                class="block w-full border-gray-200 rounded-xl shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2.5">
                            <option value="">Toate</option>
                            @foreach($counties as $county)
                                <option value="{{ $county->id }}" {{ request('county') == $county->id ? 'selected' : '' }}>
                                    {{ $county->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2 items-center">
                        <button type="submit"
                                class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                            Caută
                        </button>
                        @if(request()->hasAny(['q','category','county','city']))
                            <a href="{{ route('listings.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">✕ Resetează</a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Rezultate --}}
            @if($listings->isEmpty())
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">🔍</div>
                    <p class="text-lg font-medium text-gray-700">Nu am găsit anunțuri</p>
                    <p class="text-sm text-gray-400 mt-1">Încearcă alte filtre sau <a href="{{ route('listings.index') }}" class="text-indigo-600 hover:underline">resetează căutarea</a>.</p>
                </div>
            @else
                <p class="text-sm text-gray-500 mb-4">{{ $listings->total() }} anunțuri găsite</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($listings as $listing)
                        <div class="relative bg-white shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-all duration-200 group border border-gray-100 hover:border-indigo-200">
                            <a href="{{ route('listings.show', $listing->slug) }}" class="block">
                                <div class="h-44 overflow-hidden bg-gray-50">
                                    @if($listing->primaryImage)
                                        <img src="{{ Storage::url($listing->primaryImage->path) }}"
                                             alt="{{ $listing->primaryImage->alt_text }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-5xl bg-gradient-to-br from-indigo-50 to-violet-50">
                                            {{ $listing->category->icon ?? '🔧' }}
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">{{ $listing->category->name }}</span>
                                        @if($listing->featured_until && $listing->featured_until->isFuture())
                                            <span class="text-xs font-semibold text-amber-600 bg-amber-50 px-2.5 py-0.5 rounded-full">⭐ Top</span>
                                        @endif
                                    </div>
                                    <h3 class="font-semibold text-gray-900 line-clamp-2 text-sm leading-snug">{{ $listing->title }}</h3>
                                    <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                        {{ $listing->city->name }}, {{ $listing->county->name }}
                                    </p>
                                    <p class="mt-2.5 font-bold text-indigo-700 text-sm">{{ $listing->price_display }}</p>
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

                <div class="mt-8">{{ $listings->links() }}</div>
            @endif

        </div>
    </div>

    @auth
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
    @endauth
</x-app-layout>
