<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Cererile mele</h2>
                <a href="{{ route('customer.requests.create') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Cerere nouă
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            @if($requests->isEmpty())
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-5xl mb-4">📋</div>
                    <p class="text-lg font-medium text-gray-700">Nu ai nicio cerere încă.</p>
                    <p class="mt-1 text-sm text-gray-400">Postează ce serviciu ai nevoie și meșteșugarii te vor contacta.</p>
                    <a href="{{ route('customer.requests.create') }}"
                       class="inline-block mt-5 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                        Postează o cerere
                    </a>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 divide-y divide-gray-100">
                    @foreach($requests as $request)
                        <div class="p-5 flex items-start gap-4">
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900">{{ $request->title }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $request->city->name ?? '' }}, {{ $request->county->name ?? '' }}
                                    &bull; {{ $request->category->name ?? '' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $request->responses_count }} răspuns(uri) &bull;
                                    Postat: {{ $request->created_at->format(config('marketplace.date_display')) }}
                                </p>
                            </div>

                            {{-- Status --}}
                            <div class="flex-shrink-0 pt-0.5">
                                @php
                                    $colors = [
                                        'published' => 'bg-green-100 text-green-700',
                                        'pending'   => 'bg-yellow-100 text-yellow-700',
                                        'draft'     => 'bg-gray-100 text-gray-600',
                                        'rejected'  => 'bg-red-100 text-red-700',
                                        'matched'   => 'bg-blue-100 text-blue-700',
                                        'closed'    => 'bg-gray-100 text-gray-500',
                                    ];
                                    $color = $colors[$request->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>

                            {{-- Actiuni --}}
                            <div class="flex-shrink-0 flex items-center gap-2 pt-0.5">
                                <a href="{{ route('customer.requests.edit', $request) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editează
                                </a>

                                <form action="{{ route('customer.requests.destroy', $request) }}" method="POST"
                                      onsubmit="return confirm('Ștergi această cerere?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Șterge
                                    </button>
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
