<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cereri de servicii</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filtru --}}
            <form method="GET" class="bg-white shadow rounded-lg p-4 mb-6 flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[180px]">
                    <label class="block text-sm text-gray-700 mb-1">Cauta</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="tip serviciu, descriere..."
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
                @if(request()->hasAny(['q', 'category', 'county', 'city']))
                    <a href="{{ route('service-requests.index') }}" class="text-sm text-gray-500 self-center hover:underline">Reseteaza</a>
                @endif
            </form>

            {{-- Rezultate --}}
            @if($requests->isEmpty())
                <p class="text-center text-gray-500 py-12">Nu am gasit cereri dupa criteriile selectate.</p>
            @else
                <p class="text-sm text-gray-500 mb-3">{{ $requests->total() }} cereri gasite</p>

                <div class="space-y-4">
                    @foreach($requests as $sr)
                        <a href="{{ route('service-requests.show', $sr->slug) }}"
                           class="block bg-white shadow rounded-lg p-5 hover:shadow-md transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 text-lg">{{ $sr->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        📍 {{ $sr->city->name }}, {{ $sr->county->name }}
                                        &bull; 📂 {{ $sr->category->name }}
                                    </p>
                                    <p class="mt-2 text-gray-700 text-sm line-clamp-2">{{ $sr->description }}</p>
                                </div>
                                <div class="flex-shrink-0 text-right">
                                    <p class="text-xs text-gray-400 mt-1">{{ $sr->responses_count }} raspuns(uri)</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
