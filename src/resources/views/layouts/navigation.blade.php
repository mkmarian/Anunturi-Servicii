@php $loginUrl = route('login', ['redirect' => url()->current()]); @endphp
<nav x-data="{ open: false }" @keydown.escape.window="open = false" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo + linkuri principale --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="shrink-0 flex items-center me-6">
                    <span class="text-xl font-bold text-indigo-600">Meserii<span class="text-gray-800">Ro</span></span>
                </a>
            </div>

            {{-- Dreapta: cont sau login/register --}}
            <div class="hidden sm:flex sm:items-center sm:gap-3">
                @auth
                    {{-- Buton "Posteaza" contextual pe rol --}}
                    @if(auth()->user()->isCraftsman())
                        <a href="{{ route('craftsman.listings.create') }}"
                           class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                            + Anunț nou
                        </a>
                    @elseif(auth()->user()->isCustomer())
                        <a href="{{ route('customer.requests.create') }}"
                           class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                            + Cerere nouă
                        </a>
                    @endif

                    {{-- Dropdown cont --}}
                    <x-dropdown align="right" width="52">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-1.5 px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 focus:outline-none transition">
                                @if(Auth::user()->profile?->avatar_path)
                                    <img src="{{ asset('uploads/' . Auth::user()->profile->avatar_path) }}"
                                         class="w-9 h-9 rounded-full object-cover">
                                @else
                                    <span class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                        {{ Str::upper(Str::substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                @endif
                                <span>{{ Str::limit(Auth::user()->name, 18) }}</span>
                                <svg class="h-4 w-4 fill-current text-gray-400" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- Linkuri pe rol --}}
                            @if(auth()->user()->isCraftsman())
                                <x-dropdown-link :href="route('craftsman.listings.index')">
                                    📋 Anunțurile mele
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('craftsman.statistics')">
                                    📊 Statisticile mele
                                </x-dropdown-link>
                            @elseif(auth()->user()->isCustomer())
                                <x-dropdown-link :href="route('customer.requests.index')">
                                    📋 Cererile mele
                                </x-dropdown-link>
                            @endif
                            @if(auth()->user()->isAdmin() || auth()->user()->isModerator())
                                <x-dropdown-link :href="route('admin.listings.index')">
                                    🛡 Moderare
                                </x-dropdown-link>
                            @endif

                            <x-dropdown-link :href="route('favorites.index')">
                                🤍 Anunțuri salvate
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('messages.index')">
                                <span class="flex items-center justify-between w-full gap-3">
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m-7 6l-3-3V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H9l-3 3z" />
                                        </svg>
                                        <span>Mesaje</span>
                                    </span>
                                    <span id="nav-unread-badge" class="hidden bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 leading-none"></span>
                                </span>
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')">
                                ⚙ Contul meu
                            </x-dropdown-link>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Deconectare
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <a href="{{ $loginUrl }}"
                       class="text-sm text-gray-600 hover:text-gray-900 font-medium">Autentificare</a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                        Înregistrare
                    </a>
                @endauth
            </div>

            {{-- Hamburger mobil --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = true"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Overlay fundal --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false"
         class="fixed inset-0 bg-black/40 z-40 sm:hidden"
         style="display:none"></div>

    {{-- Drawer dreapta --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 h-full w-72 bg-white shadow-2xl z-50 flex flex-col sm:hidden transform"
         style="display:none">

        {{-- Header drawer --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            @auth
                <div class="flex items-center gap-3 min-w-0 flex-1 mr-2">
                    @if(Auth::user()->profile?->avatar_path)
                        <img src="{{ asset('uploads/' . Auth::user()->profile->avatar_path) }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold flex-shrink-0">
                            {{ Str::upper(Str::substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-800 truncate text-sm">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            @else
                <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">Meserii<span class="text-gray-800">Ro</span></a>
            @endauth
            <button @click="open = false" class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition flex-shrink-0">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Continut drawer --}}
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

            @auth

            @if(auth()->user()->isCraftsman())
                <a href="{{ route('craftsman.listings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition text-sm font-medium">📋 Anunțurile mele</a>
                <a href="{{ route('craftsman.statistics') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition text-sm font-medium">📊 Statisticile mele</a>
            @elseif(auth()->user()->isCustomer())
                <a href="{{ route('customer.requests.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition text-sm font-medium">📋 Cererile mele</a>
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isModerator())
                <a href="{{ route('admin.listings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition text-sm font-medium">🛡 Moderare</a>
            @endif

            <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition text-sm font-medium">🤍 Anunțuri salvate</a>
            <a href="{{ route('messages.index') }}" class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition text-sm font-medium">
                <span class="inline-flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5m-7 6l-3-3V7a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H9l-3 3z" /></svg>
                    <span>Mesaje</span>
                </span>
                <span id="nav-unread-badge-mobile" class="hidden bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 leading-none"></span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition text-sm font-medium">⚙ Contul meu</a>
            @else
            <div class="border-t border-gray-100 my-3"></div>
            <a href="{{ $loginUrl }}" class="flex items-center justify-center px-3 py-2.5 rounded-lg border border-indigo-600 text-indigo-600 hover:bg-indigo-50 transition text-sm font-medium">Autentificare</a>
            <a href="{{ route('register') }}" class="flex items-center justify-center px-3 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition text-sm font-medium">Înregistrare</a>
            @endauth
        </div>

        {{-- Footer drawer: actiune + deconectare --}}
        @auth
        <div class="border-t border-gray-100 p-3 space-y-2">
            @if(auth()->user()->isCraftsman())
                <a href="{{ route('craftsman.listings.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition text-sm font-medium w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Anunț nou
                </a>
            @elseif(auth()->user()->isCustomer())
                <a href="{{ route('customer.requests.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-green-600 text-white hover:bg-green-700 transition text-sm font-medium w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Cerere nouă
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 transition text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Deconectare
                </button>
            </form>
        </div>
        @endauth
    </div>

    {{-- Badge mesaje necitite --}}
    @auth
    <script>
    (function poll() {
        fetch('{{ route('messages.unread') }}', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                ['nav-unread-badge', 'nav-unread-badge-mobile'].forEach((id) => {
                    const badge = document.getElementById(id);
                    if (!badge) return;

                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                });
            })
            .catch(() => {});
        setTimeout(poll, 30000);
    })();
    </script>
    @endauth
</nav>
