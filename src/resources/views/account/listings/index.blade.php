<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Anunțurile mele</h1>
                    <p class="text-sm text-gray-500 mt-1">Gestionează și promovează anunțurile tale</p>
                </div>
                <a href="{{ route('craftsman.listings.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Anunț nou
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">{{ session('success') }}</div>
            @endif

            @if($errors->has('limit'))
                <div class="mb-4 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-xl text-sm">{{ $errors->first('limit') }}</div>
            @endif

            @if($listings->isEmpty())
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-5xl mb-4">📝</div>
                    <p class="text-lg font-medium text-gray-700">Nu ai niciun anunț</p>
                    <p class="text-sm text-gray-400 mt-1">Postează primul tău anunț și atrage clienți.</p>
                    <a href="{{ route('craftsman.listings.create') }}" class="inline-block mt-5 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                        Creează anunț
                    </a>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 divide-y divide-gray-100 overflow-hidden">
                    @foreach($listings as $listing)
                        <div class="flex items-center gap-4 p-4">
                            {{-- Thumbnail --}}
                            <div class="w-16 h-14 bg-gray-50 rounded-xl overflow-hidden flex-shrink-0 border border-gray-100">
                                @if($listing->primaryImage)
                                    <img src="{{ Storage::url($listing->primaryImage->path) }}"
                                         alt="{{ $listing->primaryImage->alt_text }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-2xl bg-indigo-50">
                                        {{ $listing->category->icon ?? '🔧' }}
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 text-sm truncate">{{ $listing->title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $listing->city->name ?? '' }}, {{ $listing->county->name ?? '' }} · {{ $listing->category->name ?? '' }}</p>
                            </div>

                            {{-- Status badge --}}
                            <div class="flex-shrink-0">
                                @php
                                    $colors = [
                                        'published' => 'bg-green-50 text-green-700 border-green-200',
                                        'pending'   => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'draft'     => 'bg-gray-50 text-gray-500 border-gray-200',
                                        'rejected'  => 'bg-red-50 text-red-700 border-red-200',
                                        'archived'  => 'bg-gray-50 text-gray-400 border-gray-200',
                                    ];
                                    $color = $colors[$listing->status] ?? 'bg-gray-50 text-gray-500 border-gray-200';
                                @endphp
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $color }}">
                                    {{ ucfirst($listing->status) }}
                                </span>
                            </div>

                            {{-- Actiuni --}}
                            <div class="flex-shrink-0 flex gap-2 items-center flex-wrap justify-end">
                                @php
                                    $isFeatured = $listing->featured_until && $listing->featured_until->isFuture();
                                @endphp
                                @if($isFeatured)
                                    <span class="text-xs bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full font-semibold">⭐ Top</span>
                                    <form action="{{ route('craftsman.promotion.deactivate', $listing) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-400 hover:text-red-500 transition" title="Oprește promovarea">✕</button>
                                    </form>
                                @elseif($listing->status === 'published')
                                    <form action="{{ route('craftsman.promotion.activate', $listing) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="days" value="30">
                                        <button type="submit" class="text-xs text-amber-600 hover:text-amber-700 border border-amber-300 px-2.5 py-0.5 rounded-full hover:bg-amber-50 transition font-semibold">
                                            ⭐ Promovează
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('craftsman.listings.edit', $listing) }}"
                                   class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">Editează</a>

                                <form action="{{ route('craftsman.listings.destroy', $listing) }}" method="POST"
                                      onsubmit="return confirm('Ștergi acest anunț?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Șterge</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">{{ $listings->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
