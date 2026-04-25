<x-admin-layout title="Cerere #{{ $serviceRequest->id }}">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Detalii --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $serviceRequest->title }}</h2>
                <p class="text-sm text-indigo-600 mb-4">{{ $serviceRequest->category->name ?? '—' }}</p>
                <p class="text-gray-700 whitespace-pre-line">{{ $serviceRequest->description }}</p>

                <div class="mt-5 grid grid-cols-2 gap-4 text-sm text-gray-600 border-t border-gray-100 pt-4">
                    <div><span class="text-gray-400">Județ:</span> {{ $serviceRequest->county->name ?? '—' }}</div>
                    <div><span class="text-gray-400">Oraș:</span> {{ $serviceRequest->city->name ?? '—' }}</div>
                    @if($serviceRequest->budget_from || $serviceRequest->budget_to)
                        <div><span class="text-gray-400">Buget:</span>
                            {{ $serviceRequest->budget_from ? number_format($serviceRequest->budget_from,0,',','.') : '?' }}
                            — {{ $serviceRequest->budget_to ? number_format($serviceRequest->budget_to,0,',','.') : '?' }} RON
                        </div>
                    @endif
                    <div><span class="text-gray-400">Postat:</span> {{ $serviceRequest->created_at->format('d.m.Y H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Sidebar actiuni --}}
        <div class="space-y-4">

            {{-- Status curent --}}
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-xs text-gray-400 mb-1">Status curent</p>
                @if($serviceRequest->status === 'pending')
                    <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full font-medium">⏳ În așteptare</span>
                @elseif($serviceRequest->status === 'published')
                    <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded-full font-medium">✅ Aprobat</span>
                @else
                    <span class="bg-red-100 text-red-600 text-sm px-3 py-1 rounded-full font-medium">❌ Respins</span>
                @endif
            </div>

            {{-- Utilizator --}}
            <div class="bg-white rounded-xl shadow p-5 text-sm">
                <p class="text-xs text-gray-400 mb-2">Postat de</p>
                <p class="font-semibold text-gray-900">{{ $serviceRequest->user->name }}</p>
                <p class="text-gray-500">{{ $serviceRequest->user->email }}</p>
                <a href="{{ route('admin.users.show', $serviceRequest->user) }}"
                   class="mt-2 block text-xs text-indigo-600 hover:underline">
                    Vezi profil utilizator →
                </a>
            </div>

            {{-- Actiuni --}}
            <div class="bg-white rounded-xl shadow p-5 space-y-2">
                @if($serviceRequest->status === 'pending')
                    <form method="POST" action="{{ route('admin.requests.approve', $serviceRequest) }}">
                        @csrf @method('PATCH')
                        <button class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 text-sm font-medium">
                            ✅ Aprobă cererea
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.requests.reject', $serviceRequest) }}">
                        @csrf @method('PATCH')
                        <button class="w-full bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 text-sm font-medium">
                            ❌ Respinge cererea
                        </button>
                    </form>
                @endif
                @if($serviceRequest->status === 'published')
                    <a href="{{ route('service-requests.show', $serviceRequest->slug) }}" target="_blank"
                       class="block w-full text-center border border-gray-200 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-50">
                        🔗 Vezi pagina publică
                    </a>
                @endif
                <form method="POST" action="{{ route('admin.requests.destroy', $serviceRequest) }}"
                      onsubmit="return confirm('Ștergi definitiv această cerere?')">
                    @csrf @method('DELETE')
                    <button class="w-full border border-red-200 text-red-500 py-2 rounded-lg hover:bg-red-50 text-sm">
                        🗑 Șterge cererea
                    </button>
                </form>
            </div>

            <a href="{{ route('admin.requests.index') }}" class="block text-center text-sm text-gray-400 hover:text-gray-600">
                ← Înapoi la lista
            </a>
        </div>
    </div>

</x-admin-layout>
