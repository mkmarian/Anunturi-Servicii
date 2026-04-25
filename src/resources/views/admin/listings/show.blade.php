<x-admin-layout title="Moderare: {{ Str::limit($listing->title, 50) }}">

    <div class="max-w-5xl grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Continut anunt --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white shadow rounded-lg p-6">
                    <h1 class="text-xl font-bold text-gray-900">{{ $listing->title }}</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $listing->category->name ?? '—' }} &bull;
                        {{ $listing->city->name ?? '' }}, {{ $listing->county->name ?? '' }}
                    </p>
                    @if($listing->price)
                        <p class="mt-2 text-indigo-700 font-semibold text-lg">{{ number_format($listing->price, 0, ',', '.') }} RON</p>
                    @endif
                    <div class="mt-4 prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($listing->description)) !!}
                    </div>
                </div>

                {{-- Imagini --}}
                @if($listing->images->isNotEmpty())
                    <div class="bg-white shadow rounded-lg p-4">
                        <h3 class="font-medium text-gray-700 mb-3">Imagini ({{ $listing->images->count() }})</h3>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($listing->images as $img)
                                <img src="{{ Storage::url($img->path) }}" class="rounded object-cover w-full h-28">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar actiuni --}}
            <div class="space-y-4">

                {{-- Status curent --}}
                <div class="bg-white shadow rounded-lg p-5">
                    @php
                        $colors = ['pending' => 'bg-yellow-100 text-yellow-800', 'published' => 'bg-green-100 text-green-800', 'rejected' => 'bg-red-100 text-red-700', 'draft' => 'bg-gray-100 text-gray-600'];
                    @endphp
                    <p class="text-sm text-gray-600 mb-2">Status curent:</p>
                    <span class="px-3 py-1 rounded-full font-semibold text-sm {{ $colors[$listing->status] ?? 'bg-gray-100' }}">
                        {{ $listing->status }}
                    </span>
                    <p class="text-xs text-gray-400 mt-2">Postat: {{ $listing->created_at->format('d-m-Y H:i') }}</p>
                </div>

                {{-- Utilizator --}}
                <div class="bg-white shadow rounded-lg p-5">
                    <h3 class="font-medium text-gray-700 mb-2">Postat de</h3>
                    <p class="font-semibold text-gray-900">{{ $listing->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $listing->user->email }}</p>
                    @if($listing->user->profile?->public_name)
                        <p class="text-sm text-gray-500">{{ $listing->user->profile->public_name }}</p>
                    @endif
                </div>

                {{-- Actiuni --}}
                <div class="bg-white shadow rounded-lg p-5 space-y-3">
                    <h3 class="font-medium text-gray-700">Acțiuni</h3>

                    @if($listing->status !== 'published')
                        <form action="{{ route('admin.listings.approve', $listing) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-full py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium text-sm">
                                ✅ Aprobă anunțul
                            </button>
                        </form>
                    @endif

                    @if($listing->status !== 'rejected')
                        <form action="{{ route('admin.listings.reject', $listing) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="w-full py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium text-sm">
                                ❌ Respinge anunțul
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('listings.show', $listing->slug) }}" target="_blank"
                       class="block w-full py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm text-center">
                        👁 Vezi public
                    </a>

                    <form action="{{ route('admin.listings.destroy', $listing) }}" method="POST"
                          onsubmit="return confirm('Ștergi definitiv acest anunț?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full py-2 border border-red-200 text-red-500 rounded-md hover:bg-red-50 text-sm">
                            🗑 Șterge definitiv
                        </button>
                    </form>
                </div>
            </div>
    </div>

</x-admin-layout>
