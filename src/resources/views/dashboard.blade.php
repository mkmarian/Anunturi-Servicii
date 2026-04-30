<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Salut --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Bună, {{ Str::words(Auth::user()->name, 1, '') }}! 👋</h1>
                <p class="text-gray-500 mt-1 text-sm">Iată ce se întâmplă în contul tău.</p>
            </div>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm">
                    <span class="text-lg">✅</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
                    <span class="text-lg">⚠️</span> {{ session('error') }}
                </div>
            @endif

            {{-- Actiuni rapide --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                @if(auth()->user()->isCraftsman())
                    <a href="{{ route('craftsman.listings.create') }}"
                       class="flex items-center gap-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl p-5 transition shadow-sm">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl flex-shrink-0">📝</div>
                        <div>
                            <p class="font-semibold text-sm">Anunț nou</p>
                            <p class="text-indigo-200 text-xs mt-0.5">Postează un serviciu</p>
                        </div>
                    </a>
                    <a href="{{ route('craftsman.listings.index') }}"
                       class="flex items-center gap-4 bg-white hover:bg-gray-50 rounded-2xl p-5 transition shadow-sm border border-gray-100">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">📋</div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Anunțurile mele</p>
                            <p class="text-gray-400 text-xs mt-0.5">Gestionează anunțurile</p>
                        </div>
                    </a>
                    <a href="{{ route('craftsman.statistics') }}"
                       class="flex items-center gap-4 bg-white hover:bg-gray-50 rounded-2xl p-5 transition shadow-sm border border-gray-100">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">📊</div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Statistici</p>
                            <p class="text-gray-400 text-xs mt-0.5">Vezi vizualizările</p>
                        </div>
                    </a>
                @elseif(auth()->user()->isCustomer())
                    <a href="{{ route('customer.requests.create') }}"
                       class="flex items-center gap-4 bg-green-600 hover:bg-green-700 text-white rounded-2xl p-5 transition shadow-sm">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-xl flex-shrink-0">✏️</div>
                        <div>
                            <p class="font-semibold text-sm">Cerere nouă</p>
                            <p class="text-green-200 text-xs mt-0.5">Caută un specialist</p>
                        </div>
                    </a>
                    <a href="{{ route('customer.requests.index') }}"
                       class="flex items-center gap-4 bg-white hover:bg-gray-50 rounded-2xl p-5 transition shadow-sm border border-gray-100">
                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">📋</div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Cererile mele</p>
                            <p class="text-gray-400 text-xs mt-0.5">Gestionează cererile</p>
                        </div>
                    </a>
                    <a href="{{ route('listings.index') }}"
                       class="flex items-center gap-4 bg-white hover:bg-gray-50 rounded-2xl p-5 transition shadow-sm border border-gray-100">
                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">🔍</div>
                        <div>
                            <p class="font-semibold text-sm text-gray-900">Caută servicii</p>
                            <p class="text-gray-400 text-xs mt-0.5">Browse anunțuri</p>
                        </div>
                    </a>
                @endif

                <a href="{{ route('messages.index') }}"
                   class="flex items-center gap-4 bg-white hover:bg-gray-50 rounded-2xl p-5 transition shadow-sm border border-gray-100">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">💬</div>
                    <div>
                        <p class="font-semibold text-sm text-gray-900">Mesaje</p>
                        <p class="text-gray-400 text-xs mt-0.5">Conversațiile tale</p>
                    </div>
                </a>

                <a href="{{ route('favorites.index') }}"
                   class="flex items-center gap-4 bg-white hover:bg-gray-50 rounded-2xl p-5 transition shadow-sm border border-gray-100">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">🤍</div>
                    <div>
                        <p class="font-semibold text-sm text-gray-900">Salvate</p>
                        <p class="text-gray-400 text-xs mt-0.5">Anunțuri favorite</p>
                    </div>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-4 bg-white hover:bg-gray-50 rounded-2xl p-5 transition shadow-sm border border-gray-100">
                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">⚙️</div>
                    <div>
                        <p class="font-semibold text-sm text-gray-900">Contul meu</p>
                        <p class="text-gray-400 text-xs mt-0.5">Editează profilul</p>
                    </div>
                </a>
            </div>

            {{-- Sectiunea: Doresc sa ofer servicii (doar clienti) --}}
            @if(auth()->user()->isCustomer())
                @php $app = $latestApplication ?? null; @endphp

                @if($app && $app->isApproved())
                    {{-- Nu ar trebui sa ajunga aici, dar ca fallback --}}
                @elseif($app && $app->isPending())
                    {{-- Cerere in asteptare --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-xl flex-shrink-0">⏳</div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">Cererea ta de meseriaș este în analiză</h3>
                                <p class="text-amber-700 text-xs mt-1">Am primit cererea ta și o analizăm. Vei fi notificat prin email după decizie.</p>
                                <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                                    <div>
                                        <span class="text-gray-500">Categorie:</span>
                                        <p class="font-medium text-gray-800">{{ $app->category?->icon }} {{ $app->category?->name ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Experiență:</span>
                                        <p class="font-medium text-gray-800">{{ $app->experience_years }} ani</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Trimisă:</span>
                                        <p class="font-medium text-gray-800">{{ $app->created_at->format('d.m.Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($app && $app->isRejected())
                    {{-- Cerere respinsa, poate retrimite --}}
                    <div x-data="{ open: false }" class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-6">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center text-xl flex-shrink-0">❌</div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 text-sm">Cererea anterioară a fost respinsă</h3>
                                    @if($app->admin_note)
                                        <p class="text-red-600 text-xs mt-1">Motiv: {{ $app->admin_note }}</p>
                                    @endif
                                    <p class="text-gray-500 text-xs mt-1">Poți trimite o nouă cerere corectând informațiile.</p>
                                </div>
                            </div>
                            <button @click="open = !open"
                                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition">
                                <span>🔄</span>
                                <span x-text="open ? 'Ascunde formularul' : 'Retrimite cererea'"></span>
                            </button>
                        </div>

                        <div x-show="open" x-transition class="border-t border-gray-100 px-6 pb-6 pt-5 bg-gray-50">
                            @include('dashboard._craftsman-application-form')
                        </div>
                    </div>
                @else
                    {{-- Niciuna, poate aplica --}}
                    <div x-data="{ open: false }" class="bg-white border border-indigo-100 rounded-2xl overflow-hidden shadow-sm">
                        <div class="p-6 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">🔨</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-sm">Dorești să oferi servicii?</h3>
                                    <p class="text-gray-500 text-xs mt-0.5">Aplică pentru a deveni meseriaș și postează anunțuri.</p>
                                </div>
                            </div>
                            <button @click="open = !open"
                                    class="flex-shrink-0 flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition">
                                <span x-text="open ? '✕ Închide' : '+ Aplică acum'"></span>
                            </button>
                        </div>

                        <div x-show="open" x-transition class="border-t border-indigo-100 px-6 pb-6 pt-5 bg-indigo-50/30">
                            @include('dashboard._craftsman-application-form')
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>

