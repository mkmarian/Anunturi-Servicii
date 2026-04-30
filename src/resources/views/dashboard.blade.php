<x-app-layout>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Salut --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Bună, {{ Str::words(Auth::user()->name, 1, '') }}! 👋</h1>
                <p class="text-gray-500 mt-1 text-sm">Iată ce se întâmplă în contul tău.</p>
            </div>

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

        </div>
    </div>
</x-app-layout>
