<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Anunțuri salvate</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($favorites->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-4xl mb-3">🤍</p>
                    <p class="text-lg">Nu ai niciun anunț salvat.</p>
                    <a href="{{ route('listings.index') }}" class="mt-4 inline-block text-indigo-600 underline">
                        Răsfoiește anunțuri
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($favorites as $fav)
                        @php $listing = $fav->listing; @endphp
                        @if($listing)
                        <div class="bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">
                            <a href="{{ route('listings.show', $listing->slug) }}">
                                @if($listing->images->isNotEmpty())
                                    <div class="h-36 overflow-hidden">
                                        <img src="{{ Storage::url($listing->images->first()->path) }}"
                                             class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="h-36 bg-indigo-50 flex items-center justify-center text-4xl">
                                        {{ $listing->category->icon ?? '🔧' }}
                                    </div>
                                @endif
                                <div class="p-4">
                                    <p class="text-xs text-indigo-600 mb-1">{{ $listing->category->name }}</p>
                                    <h3 class="font-semibold text-gray-900 line-clamp-2">{{ $listing->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        📍 {{ $listing->city->name ?? '' }}, {{ $listing->county->name ?? '' }}
                                    </p>
                                    @if($listing->price)
                                        <p class="mt-2 font-bold text-indigo-700">
                                            {{ number_format($listing->price, 0, ',', '.') }} RON
                                        </p>
                                    @endif
                                </div>
                            </a>
                            {{-- Buton sterge din favorite --}}
                            <div class="px-4 pb-4">
                                <button onclick="toggleFav(this, {{ $listing->id }})"
                                        data-listing="{{ $listing->id }}"
                                        class="text-xs text-red-500 hover:underline">
                                    ❌ Elimină din salvate
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
