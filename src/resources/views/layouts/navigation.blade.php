@php $loginUrl = route('login', ['redirect' => url()->current()]); @endphp
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo + linkuri principale --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="shrink-0 flex items-center me-6">
                    <span class="text-xl font-bold text-indigo-600">Meserii<span class="text-gray-800">Ro</span></span>
                </a>

                <div class="hidden sm:flex space-x-1">
                    <x-nav-link :href="route('listings.index')" :active="request()->routeIs('listings.*')">
                        Anunțuri
                    </x-nav-link>
                    <x-nav-link :href="route('service-requests.index')" :active="request()->routeIs('service-requests.*')">
                        Cereri
                    </x-nav-link>
                    @auth
                        <x-nav-link :href="route('messages.index')" :active="request()->routeIs('messages.*')" class="relative">
                            Mesaje
                            <span id="nav-unread-badge"
                                  class="hidden ms-1 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 leading-none">
                            </span>
                        </x-nav-link>
                    @endauth
                </div>
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
                                <span class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                    {{ Str::upper(Str::substr(Auth::user()->name, 0, 1)) }}
                                </span>
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
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Meniu responsive --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1 border-t border-gray-100">
            <x-responsive-nav-link :href="route('listings.index')">Anunțuri</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('service-requests.index')">Cereri</x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('messages.index')">Mesaje</x-responsive-nav-link>
            @endauth
        </div>

        @auth
        <div class="pt-3 pb-2 border-t border-gray-200">
            <div class="px-4 mb-2">
                <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                <div class="text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="space-y-1">
                @if(auth()->user()->isCraftsman())
                    <x-responsive-nav-link :href="route('craftsman.listings.index')">Anunțurile mele</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('craftsman.statistics')">Statisticile mele</x-responsive-nav-link>
                @elseif(auth()->user()->isCustomer())
                    <x-responsive-nav-link :href="route('customer.requests.index')">Cererile mele</x-responsive-nav-link>
                @endif
                <x-responsive-nav-link :href="route('profile.edit')">Profilul meu</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Deconectare
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @else
        <div class="pt-3 pb-2 border-t border-gray-200 space-y-1">
            <x-responsive-nav-link :href="$loginUrl">Autentificare</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('register')">Înregistrare</x-responsive-nav-link>
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
                const badge = document.getElementById('nav-unread-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            })
            .catch(() => {});
        setTimeout(poll, 30000);
    })();
    </script>
    @endauth
</nav>
