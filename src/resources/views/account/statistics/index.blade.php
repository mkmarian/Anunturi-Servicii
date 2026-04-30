<x-app-layout seoTitle="Statisticile mele">
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <div class="mb-2">
                <h1 class="text-2xl font-bold text-gray-900">Statisticile mele</h1>
                <p class="text-sm text-gray-500 mt-1">Performanța anunțurilor tale</p>
            </div>

            {{-- ── Carduri sumar ───────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-indigo-600">{{ number_format($totalViews) }}</p>
                    <p class="text-sm text-gray-500 mt-1">Vizualizări totale</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-red-500">{{ number_format($totalFavorites) }}</p>
                    <p class="text-sm text-gray-500 mt-1">Salvări la favorite</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                    <p class="text-3xl font-bold text-green-600">{{ number_format($totalMessages) }}</p>
                    <p class="text-sm text-gray-500 mt-1">Mesaje primite</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                    @if($avgRating)
                        <p class="text-3xl font-bold text-yellow-500">{{ number_format($avgRating, 1) }}</p>
                        <div class="flex justify-center gap-0.5 mt-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-sm {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                            @endfor
                        </div>
                    @else
                        <p class="text-3xl font-bold text-gray-300">—</p>
                    @endif
                    <p class="text-sm text-gray-500 mt-1">Rating mediu ({{ $totalReviews }} recenzii)</p>
                </div>
            </div>

            {{-- ── Top 5 anunțuri după vizualizări ─────────── --}}
            @if($topByViews->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700">Top anunțuri după vizualizări</h3>
                </div>
                <ul class="divide-y divide-gray-50">
                    @foreach($topByViews as $l)
                    <li class="px-6 py-4 flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 font-bold text-sm flex items-center justify-center">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('listings.show', $l->slug) }}"
                               class="font-medium text-gray-800 hover:text-indigo-600 truncate block">
                                {{ $l->title }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $l->category->name }}
                                @if($l->status !== 'published')
                                    · <span class="text-orange-500">{{ ucfirst($l->status) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-4 text-sm shrink-0">
                            <span class="flex items-center gap-1 text-gray-500" title="Vizualizări">
                                👁 {{ number_format($l->views_count) }}
                            </span>
                            <span class="flex items-center gap-1 text-gray-500" title="Favorite">
                                ❤️ {{ number_format($l->favorites_count) }}
                            </span>
                            <span class="flex items-center gap-1 text-gray-500" title="Mesaje">
                                ✉ {{ number_format($l->messages_count) }}
                            </span>
                            @if($l->reviews->count() > 0)
                            <span class="flex items-center gap-1 text-yellow-500" title="Rating">
                                ★ {{ number_format($l->reviews->avg('rating'), 1) }}
                            </span>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- ── Tabel complet anunțuri ───────────────────── --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700">Toate anunțurile ({{ $listings->count() }})</h3>
                </div>
                @if($listings->isEmpty())
                    <p class="text-center text-gray-400 py-10">Nu ai anunțuri publicate încă.</p>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3 text-left">Anunț</th>
                                <th class="px-4 py-3 text-center">Status</th>
                                <th class="px-4 py-3 text-right">👁 Views</th>
                                <th class="px-4 py-3 text-right">❤️ Fav</th>
                                <th class="px-4 py-3 text-right">✉ Msg</th>
                                <th class="px-4 py-3 text-right">★ Rating</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($listings as $l)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <a href="{{ route('craftsman.listings.edit', $l) }}"
                                       class="text-gray-800 hover:text-indigo-600 font-medium line-clamp-1">
                                        {{ $l->title }}
                                    </a>
                                    <p class="text-xs text-gray-400">{{ $l->category->name }} · {{ $l->created_at->format('d.m.Y') }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $badge = match($l->status) {
                                            'published' => 'bg-green-100 text-green-700',
                                            'pending'   => 'bg-yellow-100 text-yellow-700',
                                            'rejected'  => 'bg-red-100 text-red-700',
                                            default     => 'bg-gray-100 text-gray-600',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                                        {{ ucfirst($l->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right font-medium text-gray-700">{{ number_format($l->views_count) }}</td>
                                <td class="px-4 py-3 text-right text-gray-500">{{ number_format($l->favorites_count) }}</td>
                                <td class="px-4 py-3 text-right text-gray-500">{{ number_format($l->messages_count) }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if($l->reviews->count() > 0)
                                        <span class="text-yellow-500 font-medium">
                                            ★ {{ number_format($l->reviews->avg('rating'), 1) }}
                                        </span>
                                        <span class="text-gray-400 text-xs">({{ $l->reviews->count() }})</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 text-gray-600 font-semibold text-sm">
                            <tr>
                                <td class="px-4 py-3" colspan="2">TOTAL</td>
                                <td class="px-4 py-3 text-right">{{ number_format($totalViews) }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($totalFavorites) }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($totalMessages) }}</td>
                                <td class="px-4 py-3 text-right">
                                    @if($avgRating)
                                        ★ {{ number_format($avgRating, 1) }}
                                    @else —
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
