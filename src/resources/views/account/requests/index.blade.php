<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Cererile mele</h2>
                <a href="{{ route('customer.requests.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                    + Cerere nouă
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            @if($requests->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-lg">Nu ai nicio cerere inca.</p>
                    <p class="mt-1 text-sm">Posteaza ce serviciu ai nevoie si meseriasii te vor contacta.</p>
                    <a href="{{ route('customer.requests.create') }}" class="mt-4 inline-block text-indigo-600 underline">
                        Posteaza o cerere
                    </a>
                </div>
            @else
                <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
                    @foreach($requests as $request)
                        <div class="p-4 flex items-start gap-4">
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900">{{ $request->title }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $request->city->name ?? '' }}, {{ $request->county->name ?? '' }}
                                    &bull; {{ $request->category->name ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $request->responses_count }} raspuns(uri) &bull;
                                    Postat: {{ $request->created_at->format(config('marketplace.date_display')) }}
                                </p>
                            </div>

                            {{-- Status --}}
                            <div class="flex-shrink-0">
                                @php
                                    $colors = [
                                        'published' => 'bg-green-100 text-green-800',
                                        'pending'   => 'bg-yellow-100 text-yellow-800',
                                        'draft'     => 'bg-gray-100 text-gray-800',
                                        'rejected'  => 'bg-red-100 text-red-800',
                                        'matched'   => 'bg-blue-100 text-blue-800',
                                        'closed'    => 'bg-gray-100 text-gray-500',
                                    ];
                                    $color = $colors[$request->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $color }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>

                            {{-- Actiuni --}}
                            <div class="flex-shrink-0 flex gap-2">
                                <a href="{{ route('customer.requests.edit', $request) }}"
                                   class="text-sm text-indigo-600 hover:underline">Editeaza</a>

                                <form action="{{ route('customer.requests.destroy', $request) }}" method="POST"
                                      onsubmit="return confirm('Stergi aceasta cerere?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:underline">Sterge</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">{{ $requests->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
