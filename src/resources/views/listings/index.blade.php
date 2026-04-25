<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Anunturi servicii</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filtru --}}
            <form method="GET" class="bg-white shadow rounded-lg p-4 mb-6 flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm text-gray-700 mb-1">Cauta</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="meserias, serviciu..."
                           class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div class="min-w-[150px]">
                    <label class="block text-sm text-gray-700 mb-1">Categorie</label>
                    <select name="category"
                            class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Toate</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="min-w-[150px]">
                    <label class="block text-sm text-gray-700 mb-1">Judet</label>
                    <select name="county"
                            class="block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Toate</option>
                        @foreach($counties as $county)
                            <option value="{{ $county->id }}" {{ request('county') == $county->id ? 'selected' : '' }}>
                                {{ $county->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    Cauta
                </button>
                @if(request()->hasAny(['q','category','county','city']))
                    <a href="{{ route('listings.index') }}" class="text-sm text-gray-500 self-center hover:underline">Reseteaza</a>
                @endif
            </form>

            {{-- Rezultate --}}
            @if($listings->isEmpty())
                <p class="text-center text-gray-500 py-12">Nu am gasit anunturi dupa criteriile selectate.</p>
            @else
                <p class="text-sm text-gray-500 mb-3">{{ $listings->total() }} anunturi gasite</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($listings as $listing)
                        <a href="{{ route('listings.show', $listing->slug) }}"
                           class="bg-white shadow rounded-lg overflow-hidden hover:shadow-md transition group">

                            <div class="h-40 bg-gray-100">
                                @if($listing->primaryImage)
                                    <img src="{{ Storage::url($listing->primaryImage->path) }}"
                                         alt="{{ $listing->primaryImage->alt_text }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 text-4xl">📷</div>
                                @endif
                            </div>

                            <div class="p-4">
                                @if($listing->featured_until && $listing->featured_until->isFuture())
                                    <span class="text-xs font-semibold text-amber-600 uppercase">⭐ Promovat</span>
                                @endif
                                <h3 class="font-semibold text-gray-900 mt-1 line-clamp-2">{{ $listing->title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $listing->city->name }}, {{ $listing->county->name }}</p>
                                <p class="text-sm text-gray-500">{{ $listing->category->name }}</p>
                                <p class="mt-2 font-medium text-indigo-700">{{ $listing->price_display }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">{{ $listings->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
