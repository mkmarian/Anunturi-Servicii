<x-admin-layout title="Cerere Meseriaș — {{ $application->user->name }}">

    <div class="mb-6">
        <a href="{{ route('admin.craftsman-applications.index') }}"
           class="text-sm text-indigo-600 hover:text-indigo-800 transition">
            ← Înapoi la cereri
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Coloana stanga: detalii cerere --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Profil utilizator --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Utilizator</h2>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold text-lg">
                        {{ strtoupper(substr($application->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $application->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $application->user->email }}</p>
                        @if($application->user->phone)
                            <p class="text-sm text-gray-500">{{ $application->user->phone }}</p>
                        @endif
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-xs text-gray-500">Rol actual:</span>
                        <p class="font-medium text-gray-800">{{ ucfirst($application->user->role) }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500">Cont creat:</span>
                        <p class="font-medium text-gray-800">{{ $application->user->created_at->format('d.m.Y') }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.users.show', $application->user) }}"
                       class="text-xs text-indigo-600 hover:text-indigo-800">
                        Vezi profilul complet →
                    </a>
                </div>
            </div>

            {{-- Detalii cerere --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Detalii Cerere</h2>
                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-gray-500">Categorie servicii:</span>
                        <p class="font-medium text-gray-900 mt-0.5">
                            {{ $application->category?->icon }} {{ $application->category?->name ?? '—' }}
                            @if($application->category?->parent)
                                <span class="text-gray-400 text-xs">({{ $application->category->parent->name }})</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500">Ani de experiență:</span>
                        <p class="font-medium text-gray-900 mt-0.5">{{ $application->experience_years }} ani</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500">Descriere activitate:</span>
                        <div class="mt-1 text-sm text-gray-800 bg-gray-50 rounded-lg p-3 leading-relaxed whitespace-pre-wrap">{{ $application->description }}</div>
                    </div>
                </div>
            </div>

            {{-- Deja revizuit --}}
            @if(! $application->isPending())
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Decizie</h2>
                    <div class="flex items-center gap-2 mb-3">
                        @php $c = $application->statusColor(); @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            bg-{{ $c }}-100 text-{{ $c }}-700">
                            {{ $application->statusLabel() }}
                        </span>
                        <span class="text-xs text-gray-400">
                            de {{ $application->reviewer?->name ?? '—' }}
                            la {{ $application->reviewed_at?->format('d.m.Y H:i') }}
                        </span>
                    </div>
                    @if($application->admin_note)
                        <div class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">
                            {{ $application->admin_note }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        {{-- Coloana dreapta: actiuni --}}
        <div class="space-y-4">

            {{-- Status badge --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 text-center">
                @php $c = $application->statusColor(); @endphp
                <p class="text-xs text-gray-500 mb-2">Status cerere</p>
                <span class="px-4 py-1.5 rounded-full text-sm font-semibold
                    bg-{{ $c }}-100 text-{{ $c }}-700">
                    {{ $application->statusLabel() }}
                </span>
                <p class="text-xs text-gray-400 mt-2">Trimisă {{ $application->created_at->format('d.m.Y \l\a H:i') }}</p>
            </div>

            @if($application->isPending())
                {{-- Formular aprobare --}}
                <div class="bg-white rounded-xl border border-green-200 shadow-sm p-4">
                    <h3 class="text-sm font-semibold text-green-800 mb-3">✅ Aprobă cererea</h3>
                    <form method="POST" action="{{ route('admin.craftsman-applications.approve', $application) }}"
                          onsubmit="return confirm('Ești sigur că vrei să aprobi? Rolul utilizatorului va fi schimbat în Meseriaș.')">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="block text-xs text-gray-600 mb-1">Notă opțională pentru utilizator</label>
                            <textarea name="admin_note" rows="3" placeholder="Ex: Felicitări! Contul tău a fost activat ca meseriaș."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-300 resize-none"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                            Aprobă și activează
                        </button>
                    </form>
                </div>

                {{-- Formular respingere --}}
                <div class="bg-white rounded-xl border border-red-200 shadow-sm p-4">
                    <h3 class="text-sm font-semibold text-red-800 mb-3">❌ Respinge cererea</h3>
                    <form method="POST" action="{{ route('admin.craftsman-applications.reject', $application) }}"
                          onsubmit="return confirm('Ești sigur că vrei să respingi cererea?')">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="block text-xs text-gray-600 mb-1">Motiv respingere *</label>
                            <textarea name="admin_note" rows="3" required
                                      placeholder="Ex: Informațiile furnizate sunt insuficiente..."
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>
                            @error('admin_note')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="w-full py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition">
                            Respinge cererea
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>

</x-admin-layout>
