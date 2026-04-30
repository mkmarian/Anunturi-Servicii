<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Titlu --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Cereri de servicii</h1>
                <p class="text-sm text-gray-500 mt-1">Clienți care caută specialiști — trimite o ofertă</p>
            </div>

            {{-- Filtru --}}
            <form method="GET" class="bg-white shadow-sm rounded-2xl p-5 mb-6 border border-gray-100">
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[180px]">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Caută</label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="tip serviciu, descriere..."
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
                        @if(request()->hasAny(['q', 'category', 'county', 'city']))
                            <a href="{{ route('service-requests.index') }}" class="text-sm text-gray-400 hover:text-gray-600 transition">✕ Resetează</a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Rezultate --}}
            @if($requests->isEmpty())
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">📋</div>
                    <p class="text-lg font-medium text-gray-700">Nu am găsit cereri</p>
                    <p class="text-sm text-gray-400 mt-1">Încearcă alte filtre sau <a href="{{ route('service-requests.index') }}" class="text-indigo-600 hover:underline">resetează căutarea</a>.</p>
                </div>
            @else
                <p class="text-sm text-gray-500 mb-4">{{ $requests->total() }} cereri găsite</p>

                <div class="space-y-3">
                    @foreach($requests as $sr)
                        <a href="{{ route('service-requests.show', $sr->slug) }}"
                           class="flex items-start justify-between gap-4 bg-white shadow-sm rounded-2xl p-5 hover:shadow-md hover:border-indigo-200 transition-all border border-gray-100 group">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                    <span class="text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full">{{ $sr->category->name }}</span>
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                        {{ $sr->city->name }}, {{ $sr->county->name }}
                                    </span>
                                </div>
                                <h3 class="font-semibold text-gray-900 text-sm group-hover:text-indigo-700 transition-colors">{{ $sr->title }}</h3>
                                <p class="mt-1.5 text-gray-500 text-sm line-clamp-2">{{ $sr->description }}</p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <span class="inline-block text-xs font-semibold bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                    {{ $sr->responses_count }} oferte
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
