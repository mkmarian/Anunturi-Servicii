<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Anunturile mele</h2>
            <a href="{{ route('craftsman.listings.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                + Anunt nou
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            @if($errors->has('limit'))
                <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded-md">{{ $errors->first('limit') }}</div>
            @endif

            @if($listings->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-lg">Nu ai niciun anunt inca.</p>
                    <a href="{{ route('craftsman.listings.create') }}" class="mt-4 inline-block text-indigo-600 underline">Creeaza primul anunt</a>
                </div>
            @else
                <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
                    @foreach($listings as $listing)
                        <div class="p-4 flex items-center gap-4">
                            {{-- Thumbnail --}}
                            <div class="w-20 h-16 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                                @if($listing->primaryImage)
                                    <img src="{{ Storage::url($listing->primaryImage->path) }}"
                                         alt="{{ $listing->primaryImage->alt_text }}"
                                         class="w-full h-full object-cover">
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 truncate">{{ $listing->title }}</p>
                                <p class="text-sm text-gray-500">{{ $listing->city->name ?? '' }}, {{ $listing->county->name ?? '' }}</p>
                                <p class="text-sm text-gray-500">{{ $listing->category->name ?? '' }}</p>
                            </div>

                            {{-- Status badge --}}
                            <div class="flex-shrink-0">
                                @php
                                    $colors = [
                                        'published' => 'bg-green-100 text-green-800',
                                        'pending'   => 'bg-yellow-100 text-yellow-800',
                                        'draft'     => 'bg-gray-100 text-gray-800',
                                        'rejected'  => 'bg-red-100 text-red-800',
                                        'archived'  => 'bg-gray-100 text-gray-500',
                                    ];
                                    $color = $colors[$listing->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $color }}">
                                    {{ ucfirst($listing->status) }}
                                </span>
                            </div>

                            {{-- Actiuni --}}
                            <div class="flex-shrink-0 flex gap-2 items-center">
                                {{-- Featured badge / buton promovare --}}
                                @php
                                    $isFeatured = $listing->featured_until && $listing->featured_until->isFuture();
                                @endphp
                                @if($isFeatured)
                                    <span class="text-xs bg-yellow-100 text-yellow-700 border border-yellow-300 px-2 py-0.5 rounded-full font-medium">
                                        ⭐ Featured
                                    </span>
                                    <form action="{{ route('craftsman.promotion.deactivate', $listing) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-gray-400 hover:text-red-500" title="Oprește promovarea">✕</button>
                                    </form>
                                @elseif($listing->status === 'published')
                                    <form action="{{ route('craftsman.promotion.activate', $listing) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="days" value="30">
                                        <button type="submit" class="text-xs text-yellow-600 hover:text-yellow-700 border border-yellow-400 px-2 py-0.5 rounded-full hover:bg-yellow-50 transition">
                                            ⭐ Promovează
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('craftsman.listings.edit', $listing) }}"
                                   class="text-sm text-indigo-600 hover:underline">Editeaza</a>

                                <form action="{{ route('craftsman.listings.destroy', $listing) }}" method="POST"
                                      onsubmit="return confirm('Stergi acest anunt?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Sterge</button>
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
