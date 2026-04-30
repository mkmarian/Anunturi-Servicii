<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Anunțuri salvate</h1>
                <p class="text-sm text-gray-500 mt-1">Anunțurile pe care le-ai salvat la favorite</p>
            </div>

            @if($favorites->isEmpty())
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-5xl mb-4">🤍</div>
                    <p class="text-lg font-medium text-gray-700">Nu ai niciun anunț salvat</p>
                    <p class="text-sm text-gray-400 mt-1">Apasă pe inimă pentru a salva anunțuri.</p>
                    <a href="{{ route('listings.index') }}" class="inline-block mt-5 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                        Răsfoiește anunțuri
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($favorites as $fav)
                        @php $listing = $fav->listing; @endphp
                        @if($listing)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all group">
                            <a href="{{ route('listings.show', $listing->slug) }}">
                                @if($listing->images->isNotEmpty())
                                    <div class="h-40 overflow-hidden">
                                        <img src="{{ Storage::url($listing->images->first()->path) }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="h-40 bg-gradient-to-br from-indigo-50 to-violet-50 flex items-center justify-center text-4xl">
                                        {{ $listing->category->icon ?? '🔧' }}
                                    </div>
                                @endif
                                <div class="p-4">
                                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">{{ $listing->category->name }}</span>
                                    <h3 class="font-semibold text-gray-900 line-clamp-2 text-sm mt-2">{{ $listing->title }}</h3>
                                    <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                        {{ $listing->city->name ?? '' }}, {{ $listing->county->name ?? '' }}
                                    </p>
                                    @if($listing->price)
                                        <p class="mt-2 font-bold text-indigo-700 text-sm">
                                            {{ number_format($listing->price, 0, ',', '.') }} RON
                                        </p>
                                    @endif
                                </div>
                            </a>
                            <div class="px-4 pb-4">
                                <button onclick="toggleFav(this, {{ $listing->id }})"
                                        data-listing="{{ $listing->id }}"
                                        class="text-xs font-semibold text-red-500 hover:text-red-700 transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    Elimină din salvate
                                </button>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                <div class="mt-8">{{ $favorites->links() }}</div>
            @endif
        </div>
    </div>

    <script>
    function toggleFav(btn, listingId) {
        fetch('/favorite/' + listingId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.favorited) {
                btn.closest('.bg-white').remove();
            }
        });
    }
    </script>
</x-app-layout>
