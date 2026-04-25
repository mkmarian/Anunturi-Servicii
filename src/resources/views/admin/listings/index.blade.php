<x-admin-layout title="Moderare anunțuri">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
            @endif

            {{-- Tabs status --}}
            <div class="flex gap-2 mb-6 flex-wrap">
                @foreach(['pending' => ['label' => '⏳ În așteptare', 'color' => 'yellow'], 'published' => ['label' => '✅ Aprobate', 'color' => 'green'], 'rejected' => ['label' => '❌ Respinse', 'color' => 'red'], 'all' => ['label' => '📋 Toate', 'color' => 'gray']] as $s => $info)
                    <a href="{{ route('admin.listings.index', ['status' => $s]) }}"
                       class="px-4 py-2 rounded-full text-sm font-medium transition
                              {{ $status === $s ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }}">
                        {{ $info['label'] }}
                        @if(isset($counts[$s]))
                            <span class="ms-1 {{ $status === $s ? 'text-indigo-200' : 'text-gray-400' }}">
                                ({{ $counts[$s] }})
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($listings->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <p class="text-4xl mb-2">🎉</p>
                    <p>Niciun anunț în această categorie.</p>
                </div>
            @else
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Anunț</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Utilizator</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden lg:table-cell">Categorie / Locație</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($listings as $listing)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.listings.show', $listing) }}"
                                           class="font-medium text-indigo-700 hover:underline">
                                            {{ Str::limit($listing->title, 55) }}
                                        </a>
                                        @if($listing->price)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ number_format($listing->price, 0, ',', '.') }} RON</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 hidden md:table-cell text-sm text-gray-600">
                                        {{ $listing->user->name }}<br>
                                        <span class="text-xs text-gray-400">{{ $listing->user->email }}</span>
                                    </td>
                                    <td class="px-4 py-3 hidden lg:table-cell text-sm text-gray-500">
                                        {{ $listing->category->name ?? '—' }}<br>
                                        {{ $listing->city->name ?? '' }}{{ $listing->county ? ', '.$listing->county->name : '' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $colors = ['pending' => 'bg-yellow-100 text-yellow-800', 'published' => 'bg-green-100 text-green-800', 'rejected' => 'bg-red-100 text-red-700', 'draft' => 'bg-gray-100 text-gray-600'];
                                        @endphp
                                        <span class="px-2 py-0.5 text-xs rounded-full font-medium {{ $colors[$listing->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ $listing->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-400 whitespace-nowrap">
                                        {{ $listing->created_at->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2 items-center justify-end">
                                            @if($listing->status === 'pending')
                                                <form action="{{ route('admin.listings.approve', $listing) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                                        Aprobă
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.listings.reject', $listing) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            class="px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                                        Respinge
                                                    </button>
                                                </form>
                                            @elseif($listing->status === 'published')
                                                <form action="{{ route('admin.listings.reject', $listing) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            class="px-3 py-1 border border-red-300 text-red-600 text-xs rounded hover:bg-red-50">
                                                        Retrage
                                                    </button>
                                                </form>
                                            @elseif($listing->status === 'rejected')
                                                <form action="{{ route('admin.listings.approve', $listing) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                            class="px-3 py-1 border border-green-300 text-green-600 text-xs rounded hover:bg-green-50">
                                                        Re-aprobă
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('admin.listings.show', $listing) }}"
                                               class="text-xs text-gray-500 hover:underline">Detalii</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">{{ $listings->links() }}</div>
            @endif

</x-admin-layout>
