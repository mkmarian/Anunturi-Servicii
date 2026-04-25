<x-admin-layout title="Dashboard">

    {{-- ── Stat cards ──────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        {{-- Anunturi pending --}}
        <a href="{{ route('admin.listings.index', ['status'=>'pending']) }}"
           class="bg-white rounded-xl p-5 shadow hover:shadow-md transition border-l-4 border-yellow-400">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['listings']['pending'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Anunțuri în așteptare</p>
        </a>

        {{-- Anunturi publicate --}}
        <a href="{{ route('admin.listings.index', ['status'=>'published']) }}"
           class="bg-white rounded-xl p-5 shadow hover:shadow-md transition border-l-4 border-green-400">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['listings']['published'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Anunțuri active</p>
        </a>

        {{-- Cereri pending --}}
        <a href="{{ route('admin.requests.index', ['status'=>'pending']) }}"
           class="bg-white rounded-xl p-5 shadow hover:shadow-md transition border-l-4 border-blue-400">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['requests']['pending'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Cereri în așteptare</p>
        </a>

        {{-- Utilizatori --}}
        <a href="{{ route('admin.users.index') }}"
           class="bg-white rounded-xl p-5 shadow hover:shadow-md transition border-l-4 border-indigo-400">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['users']['total'] }}</p>
            <p class="text-sm text-gray-500 mt-1">Utilizatori totali</p>
        </a>
    </div>

    {{-- ── Statistici detaliate ─────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">

        {{-- Anunturi --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-semibold text-gray-800 mb-3">📋 Anunțuri</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Total</span><span class="font-medium">{{ $stats['listings']['total'] }}</span></div>
                <div class="flex justify-between"><span class="text-yellow-600">⏳ Pending</span><span class="font-medium">{{ $stats['listings']['pending'] }}</span></div>
                <div class="flex justify-between"><span class="text-green-600">✅ Publicate</span><span class="font-medium">{{ $stats['listings']['published'] }}</span></div>
                <div class="flex justify-between"><span class="text-red-500">❌ Respinse</span><span class="font-medium">{{ $stats['listings']['rejected'] }}</span></div>
            </div>
            <a href="{{ route('admin.listings.index') }}" class="mt-4 block text-xs text-indigo-600 hover:underline">
                Gestionează anunțuri →
            </a>
        </div>

        {{-- Cereri --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-semibold text-gray-800 mb-3">📩 Cereri servicii</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Total</span><span class="font-medium">{{ $stats['requests']['total'] }}</span></div>
                <div class="flex justify-between"><span class="text-yellow-600">⏳ Pending</span><span class="font-medium">{{ $stats['requests']['pending'] }}</span></div>
                <div class="flex justify-between"><span class="text-green-600">✅ Publicate</span><span class="font-medium">{{ $stats['requests']['published'] }}</span></div>
            </div>
            <a href="{{ route('admin.requests.index') }}" class="mt-4 block text-xs text-indigo-600 hover:underline">
                Gestionează cereri →
            </a>
        </div>

        {{-- Utilizatori --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-semibold text-gray-800 mb-3">👥 Utilizatori</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Total</span><span class="font-medium">{{ $stats['users']['total'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Meșteșugari</span><span class="font-medium">{{ $stats['users']['craftsmen'] }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Clienți</span><span class="font-medium">{{ $stats['users']['customers'] }}</span></div>
                <div class="flex justify-between"><span class="text-blue-600">Înregistrați azi</span><span class="font-medium">{{ $stats['users']['new_today'] }}</span></div>
            </div>
            <a href="{{ route('admin.users.index') }}" class="mt-4 block text-xs text-indigo-600 hover:underline">
                Gestionează utilizatori →
            </a>
        </div>
    </div>

    {{-- ── Activitate recenta ───────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Anunturi pending recente --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-semibold text-gray-800 mb-4">⏳ Anunțuri în așteptare</h3>
            @forelse($recentPendingListings as $listing)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $listing->title }}</p>
                        <p class="text-xs text-gray-400">{{ $listing->user->name }} · {{ $listing->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.listings.show', $listing) }}"
                       class="ml-3 flex-shrink-0 text-xs text-indigo-600 hover:underline">Verifică</a>
                </div>
            @empty
                <p class="text-sm text-gray-400">Niciun anunț în așteptare 🎉</p>
            @endforelse
        </div>

        {{-- Cereri pending recente --}}
        <div class="bg-white rounded-xl shadow p-5">
            <h3 class="font-semibold text-gray-800 mb-4">⏳ Cereri în așteptare</h3>
            @forelse($recentPendingRequests as $sr)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $sr->title }}</p>
                        <p class="text-xs text-gray-400">{{ $sr->user->name }} · {{ $sr->created_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('admin.requests.show', $sr) }}"
                       class="ml-3 flex-shrink-0 text-xs text-indigo-600 hover:underline">Verifică</a>
                </div>
            @empty
                <p class="text-sm text-gray-400">Nicio cerere în așteptare 🎉</p>
            @endforelse
        </div>
    </div>

</x-admin-layout>
