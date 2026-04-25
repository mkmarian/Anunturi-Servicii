<x-admin-layout title="Utilizator: {{ $user->name }}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info utilizator --}}
        <div class="space-y-5">
            <div class="bg-white rounded-xl shadow p-6 text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-2xl font-bold mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h2 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                <p class="text-gray-400 text-xs mt-1">{{ $user->phone ?? 'Fără telefon' }}</p>

                <div class="mt-3 flex justify-center gap-2">
                    <span class="text-xs px-2 py-0.5 rounded-full
                        {{ $user->role === 'craftsman' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $user->role === 'customer' ? 'bg-gray-100 text-gray-600' : '' }}
                        {{ in_array($user->role, ['admin','moderator']) ? 'bg-purple-100 text-purple-700' : '' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                    @if($user->status === 'active')
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Activ</span>
                    @else
                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Suspendat</span>
                    @endif
                </div>

                <p class="text-xs text-gray-400 mt-3">
                    Înregistrat {{ $user->created_at->format('d.m.Y') }}
                </p>
            </div>

            {{-- Actiuni --}}
            @if($user->id !== auth()->id())
                <div class="bg-white rounded-xl shadow p-5">
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                        @csrf @method('PATCH')
                        <button class="w-full py-2 rounded-lg text-sm font-medium
                            {{ $user->status === 'active'
                                ? 'bg-red-50 text-red-600 border border-red-200 hover:bg-red-100'
                                : 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100' }}">
                            {{ $user->status === 'active' ? '🚫 Suspendă contul' : '✅ Activează contul' }}
                        </button>
                    </form>
                </div>
            @endif

            <a href="{{ route('admin.users.index') }}" class="block text-center text-sm text-gray-400 hover:text-gray-600">
                ← Înapoi la lista
            </a>
        </div>

        {{-- Activitate --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Anunturi --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-800 mb-3">
                    📋 Anunțuri ({{ $user->listings->count() }})
                </h3>
                @forelse($user->listings->take(5) as $listing)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-sm text-gray-900">{{ $listing->title }}</p>
                            <p class="text-xs text-gray-400">{{ $listing->created_at->format('d.m.Y') }}</p>
                        </div>
                        @if($listing->status === 'pending')
                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">Pending</span>
                        @elseif($listing->status === 'published')
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Publicat</span>
                        @else
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full">Respins</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Niciun anunț.</p>
                @endforelse
            </div>

            {{-- Cereri --}}
            <div class="bg-white rounded-xl shadow p-5">
                <h3 class="font-semibold text-gray-800 mb-3">
                    📩 Cereri servicii ({{ $user->serviceRequests->count() }})
                </h3>
                @forelse($user->serviceRequests->take(5) as $sr)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="text-sm text-gray-900">{{ $sr->title }}</p>
                            <p class="text-xs text-gray-400">{{ $sr->created_at->format('d.m.Y') }}</p>
                        </div>
                        <span class="text-xs {{ $sr->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }} px-2 py-0.5 rounded-full">
                            {{ ucfirst($sr->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Nicio cerere.</p>
                @endforelse
            </div>
        </div>
    </div>

</x-admin-layout>
