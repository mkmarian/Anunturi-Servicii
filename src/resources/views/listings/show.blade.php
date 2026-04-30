<x-app-layout
    :seoTitle="$listing->title"
    :seoDescription="Str::limit(strip_tags($listing->short_description ?? $listing->description ?? ''), 160)"
    :ogImage="$listing->primaryImage ? asset('storage/' . $listing->primaryImage->path) : null"
    ogType="article"
    :canonical="route('listings.show', $listing->slug)"
>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-xs text-gray-400 mb-5 flex items-center gap-1.5">
                <a href="{{ route('listings.index') }}" class="hover:text-indigo-600 transition">Anunțuri</a>
                <span>›</span>
                <a href="{{ route('listings.index', ['category' => $listing->category->slug ?? '']) }}" class="hover:text-indigo-600 transition">{{ $listing->category->name }}</a>
                <span>›</span>
                <span class="text-gray-600 font-medium truncate">{{ Str::limit($listing->title, 50) }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Coloana principala --}}
                <div class="lg:col-span-2 space-y-5">

                    {{-- Galerie imagini --}}
                    @if($listing->images->isNotEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <img src="{{ Storage::url($listing->images->first()->path) }}"
                                 alt="{{ $listing->images->first()->alt_text }}"
                                 class="w-full max-h-96 object-cover" id="main-img">
                            @if($listing->images->count() > 1)
                                <div class="flex gap-2 p-3 overflow-x-auto">
                                    @foreach($listing->images as $img)
                                        <img src="{{ Storage::url($img->path) }}"
                                             alt="{{ $img->alt_text }}"
                                             onclick="document.getElementById('main-img').src=this.src"
                                             class="w-20 h-16 object-cover rounded-xl cursor-pointer border-2 border-transparent hover:border-indigo-500 transition flex-shrink-0">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-gradient-to-br from-indigo-50 to-violet-50 rounded-2xl h-48 flex items-center justify-center text-6xl border border-gray-100">
                            {{ $listing->category->icon ?? '🔧' }}
                        </div>
                    @endif

                    {{-- Detalii anunt --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        @if($listing->featured_until?->isFuture())
                            <span class="inline-flex items-center gap-1 mb-3 text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">
                                ⭐ Anunț promovat
                            </span>
                        @endif

                        <h1 class="text-2xl font-bold text-gray-900 leading-snug">{{ $listing->title }}</h1>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-50 border border-gray-200 px-2.5 py-0.5 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                {{ $listing->city->name }}, {{ $listing->county->name }}
                            </span>
                            <span class="flex items-center gap-1 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-full">
                                {{ $listing->category->icon ?? '📂' }} {{ $listing->category->name }}
                            </span>
                            <span class="flex items-center gap-1 text-xs text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-0.5 rounded-full">
                                👁 {{ $listing->views_count }} vizualizări
                            </span>
                            @if($listing->published_at)
                            <span class="flex items-center gap-1 text-xs text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-0.5 rounded-full">
                                📅 {{ $listing->published_at->format(config('marketplace.date_display')) }}
                            </span>
                            @endif
                        </div>

                        <div class="mt-5 text-2xl font-bold text-indigo-700">
                            {{ $listing->price_display }}
                        </div>

                        @if($listing->short_description)
                            <p class="mt-4 text-gray-700 font-medium text-sm leading-relaxed">{{ $listing->short_description }}</p>
                        @endif

                        <div class="mt-4 prose prose-sm max-w-none text-gray-600 leading-relaxed">
                            {!! nl2br(e($listing->description)) !!}
                        </div>
                    </div>
                </div>

                {{-- Sidebar: meserias + contact --}}
                <div class="space-y-4">

                    {{-- Card meserias --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        @php $profile = $listing->user->profile; @endphp

                        <div class="flex items-center gap-3 mb-4">
                            @if($profile?->avatar_path)
                                <img src="{{ asset('uploads/' . $profile->avatar_path) }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-100">
                            @else
                                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-xl font-bold text-indigo-600">
                                    {{ Str::upper(Str::substr($listing->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $profile?->display_name ?? $listing->user->name }}</h3>
                                @if($profile?->company_name)
                                    <p class="text-xs text-gray-400">{{ $profile->company_name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Contact --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-3">
                        <h3 class="font-semibold text-gray-900 text-sm">Contact</h3>

                        @if($listing->show_phone && $listing->phone)
                            <a href="tel:{{ $listing->phone }}"
                               class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition text-sm font-semibold">
                                📞 {{ $listing->phone }}
                            </a>
                        @endif

                        @auth
                            @if(auth()->id() !== $listing->user_id)
                            <form action="{{ route('messages.start.listing', $listing) }}" method="POST">
                                @csrf
                                <button type="submit"
                                   class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition text-sm font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                    Trimite mesaj
                                </button>
                            </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                               class="flex items-center justify-center gap-2 w-full px-4 py-2.5 border border-indigo-600 text-indigo-600 rounded-xl hover:bg-indigo-50 transition text-sm font-semibold">
                                Autentifică-te pentru a scrie
                            </a>
                        @endauth
                    </div>

                    {{-- Favorite + Raport --}}
                    @auth
                        <div class="flex justify-between items-center px-1">
                            <button id="fav-btn"
                                    onclick="toggleFav()"
                                    class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-500 transition">
                                <span id="fav-icon">
                                    {{ auth()->user()->favorites()->where('listing_id', $listing->id)->exists() ? '❤️' : '🤍' }}
                                </span>
                                <span id="fav-label">
                                    {{ auth()->user()->favorites()->where('listing_id', $listing->id)->exists() ? 'Salvat' : 'Salvează' }}
                                </span>
                            </button>
                            <a href="#" class="text-xs text-gray-400 hover:text-red-500 transition">🚩 Raportează</a>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════ RECENZII ═══════════════════ --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">

        <div class="flex items-center gap-3 mb-6">
            <h2 class="text-xl font-bold text-gray-800">Recenzii</h2>
            @if($reviews->count() > 0)
                <div class="flex items-center gap-1 text-yellow-400 text-lg">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                    @endfor
                </div>
                <span class="text-gray-700 font-semibold text-sm">{{ number_format($avgRating, 1) }}</span>
                <span class="text-sm text-gray-400">({{ $reviews->count() }} {{ Str::plural('recenzie', $reviews->count()) }})</span>
            @else
                <span class="text-sm text-gray-400">Fără recenzii încă</span>
            @endif
        </div>

        <div class="grid md:grid-cols-3 gap-8">

            {{-- Lista recenzii --}}
            <div class="md:col-span-2 space-y-4">
                @forelse($reviews as $review)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm uppercase">
                                    {{ mb_substr($review->reviewer->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">{{ $review->reviewer->name ?? 'Utilizator șters' }}</p>
                                    <p class="text-xs text-gray-400">{{ $review->created_at->format('d.m.Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-0.5 text-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="mt-3 text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                        @endif
                        @auth
                            @if(Auth::id() === $review->reviewer_id || Auth::user()->isModerator())
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="mt-3 text-right">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Ștergi această recenzie?')"
                                            class="text-xs text-red-400 hover:text-red-600 transition">Șterge</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                @empty
                    <div class="bg-gray-50 rounded-2xl p-8 text-center text-gray-400">
                        <p class="text-3xl mb-2">⭐</p>
                        <p>Fii primul care lasă o recenzie!</p>
                    </div>
                @endforelse
            </div>

            {{-- Formular adaugare recenzie --}}
            <div>
                @auth
                    @if(Auth::id() !== $listing->user_id)
                        @if($userReview)
                            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 text-center">
                                <p class="text-green-700 font-medium text-sm">✓ Ai lăsat deja o recenzie</p>
                                <div class="flex justify-center gap-0.5 mt-2 text-xl">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                        @else
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                                <h3 class="font-semibold text-gray-800 mb-4 text-sm">Adaugă o recenzie</h3>
                                <form action="{{ route('reviews.store', $listing) }}" method="POST" id="review-form">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-xs text-gray-500 uppercase tracking-wide mb-2">Calificativ</label>
                                        <div class="flex gap-1 text-3xl" id="star-picker">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button"
                                                        data-value="{{ $i }}"
                                                        class="star-btn text-gray-300 hover:text-yellow-400 transition cursor-pointer leading-none">★</button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" id="rating-input" value="">
                                        @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-xs text-gray-500 uppercase tracking-wide mb-1">Comentariu (opțional)</label>
                                        <textarea name="comment" rows="4"
                                                  class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none resize-none"
                                                  placeholder="Descrie experiența ta cu acest meșter...">{{ old('comment') }}</textarea>
                                        @error('comment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <button type="submit"
                                            id="review-submit"
                                            disabled
                                            class="w-full py-2.5 px-4 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                        Trimite recenzia
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 text-center">
                        <p class="text-gray-500 text-sm mb-3">Trebuie să fii autentificat pentru a lăsa o recenzie.</p>
                        <a href="{{ route('login') }}"
                           class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                            Autentifică-te
                        </a>
                    </div>
                @endauth
            </div>

        </div>
    </div>

</x-app-layout>

@auth
<script>
function toggleFav() {
    fetch('{{ route('favorites.toggle', $listing) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('fav-icon').textContent  = data.favorited ? '❤️' : '🤍';
        document.getElementById('fav-label').textContent = data.favorited ? 'Salvat' : 'Salvează';
    });
}
</script>
@endauth

<script>
// Star picker interactiv
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-btn');
    const input = document.getElementById('rating-input');
    const submitBtn = document.getElementById('review-submit');

    if (!stars.length) return;

    function setStars(val) {
        stars.forEach(s => {
            const v = parseInt(s.dataset.value);
            s.classList.toggle('text-yellow-400', v <= val);
            s.classList.toggle('text-gray-300', v > val);
        });
        input.value = val;
        if (submitBtn) submitBtn.disabled = false;
    }

    stars.forEach(star => {
        star.addEventListener('click', () => setStars(parseInt(star.dataset.value)));
        star.addEventListener('mouseenter', () => {
            const val = parseInt(star.dataset.value);
            stars.forEach(s => {
                s.classList.toggle('text-yellow-300', parseInt(s.dataset.value) <= val);
            });
        });
        star.addEventListener('mouseleave', () => {
            const current = parseInt(input.value) || 0;
            stars.forEach(s => {
                const v = parseInt(s.dataset.value);
                s.classList.toggle('text-yellow-400', v <= current);
                s.classList.toggle('text-yellow-300', false);
                s.classList.toggle('text-gray-300', v > current);
            });
        });
    });
});
</script>
                @endforelse
            </div>

            {{-- Formular adaugare recenzie --}}
            <div>
                @auth
                    @if(Auth::id() !== $listing->user_id)
                        @if($userReview)
                            <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-center">
                                <p class="text-green-700 font-medium text-sm">✓ Ai lăsat deja o recenzie</p>
                                <div class="flex justify-center gap-0.5 mt-2 text-xl">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $userReview->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                    @endfor
                                </div>
                            </div>
                        @else
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                                <h3 class="font-semibold text-gray-800 mb-4">Adaugă o recenzie</h3>
                                <form action="{{ route('reviews.store', $listing) }}" method="POST" id="review-form">
                                    @csrf
                                    {{-- Stele interactive --}}
                                    <div class="mb-4">
                                        <label class="block text-sm text-gray-600 mb-2">Calificativ</label>
                                        <div class="flex gap-1 text-3xl" id="star-picker">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button"
                                                        data-value="{{ $i }}"
                                                        class="star-btn text-gray-300 hover:text-yellow-400 transition cursor-pointer leading-none">★</button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" id="rating-input" value="">
                                        @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    {{-- Comentariu --}}
                                    <div class="mb-4">
                                        <label class="block text-sm text-gray-600 mb-1">Comentariu (opțional)</label>
                                        <textarea name="comment" rows="4"
                                                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none resize-none"
                                                  placeholder="Descrie experiența ta cu acest meșter...">{{ old('comment') }}</textarea>
                                        @error('comment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <button type="submit"
                                            id="review-submit"
                                            disabled
                                            class="w-full py-2 px-4 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                        Trimite recenzia
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
                        <p class="text-gray-500 text-sm mb-3">Trebuie să fii autentificat pentru a lăsa o recenzie.</p>
                        <a href="{{ route('login') }}"
                           class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700">
                            Autentifică-te
                        </a>
                    </div>
                @endauth
            </div>

        </div>
    </div>

</x-app-layout>

@auth
<script>
function toggleFav() {
    fetch('{{ route('favorites.toggle', $listing) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('fav-icon').textContent  = data.favorited ? '❤️' : '🤍';
        document.getElementById('fav-label').textContent = data.favorited ? 'Salvat' : 'Salvează';
    });
}
</script>
@endauth

<script>
// Star picker interactiv
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-btn');
    const input = document.getElementById('rating-input');
    const submitBtn = document.getElementById('review-submit');

    if (!stars.length) return;

    function setStars(val) {
        stars.forEach(s => {
            const v = parseInt(s.dataset.value);
            s.classList.toggle('text-yellow-400', v <= val);
            s.classList.toggle('text-gray-300', v > val);
        });
        input.value = val;
        if (submitBtn) submitBtn.disabled = false;
    }

    stars.forEach(star => {
        star.addEventListener('click', () => setStars(parseInt(star.dataset.value)));
        star.addEventListener('mouseenter', () => {
            const val = parseInt(star.dataset.value);
            stars.forEach(s => {
                s.classList.toggle('text-yellow-300', parseInt(s.dataset.value) <= val);
            });
        });
        star.addEventListener('mouseleave', () => {
            const current = parseInt(input.value) || 0;
            stars.forEach(s => {
                const v = parseInt(s.dataset.value);
                s.classList.toggle('text-yellow-400', v <= current);
                s.classList.toggle('text-yellow-300', false);
                s.classList.toggle('text-gray-300', v > current);
            });
        });
    });
});
</script>
