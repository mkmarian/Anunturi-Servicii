<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col lg:flex-row gap-8 items-start">

                {{-- Coloana stânga: card profil --}}
                <div class="w-full lg:w-72 flex-shrink-0 space-y-5">

                    {{-- Card avatar + info --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-br from-indigo-500 to-indigo-700 h-20"></div>
                        <div class="px-6 pb-6 -mt-10 text-center">
                            <div class="relative inline-block group cursor-pointer" onclick="document.getElementById('avatar').click()">
                                @if($user->profile?->avatar_path)
                                    <img id="avatar-preview"
                                         src="{{ asset('uploads/' . $user->profile->avatar_path) }}"
                                         class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-md mx-auto">
                                @else
                                    <div id="avatar-initials"
                                         class="w-20 h-20 rounded-full bg-indigo-100 ring-4 ring-white shadow-md mx-auto flex items-center justify-center text-3xl font-bold text-indigo-600">
                                        {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                    </div>
                                    <img id="avatar-preview" src=""
                                         class="w-20 h-20 rounded-full object-cover ring-4 ring-white shadow-md mx-auto hidden">
                                @endif
                                <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="mt-3 font-bold text-gray-900 text-lg">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            @if($user->profile?->public_name || $user->profile?->company_name)
                                <p class="text-sm text-indigo-600 mt-1">{{ $user->profile->company_name ?? $user->profile->public_name }}</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">Membru din {{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>

                    {{-- Navigare rapidă --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 space-y-1">
                        @if(auth()->user()->isCraftsman())
                            <a href="{{ route('craftsman.listings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <span>📋</span> Anunțurile mele
                            </a>
                            <a href="{{ route('craftsman.statistics') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <span>📊</span> Statisticile mele
                            </a>
                        @elseif(auth()->user()->isCustomer())
                            <a href="{{ route('customer.requests.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                <span>📋</span> Cererile mele
                            </a>
                        @endif
                        <a href="{{ route('favorites.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <span>🤍</span> Anunțuri salvate
                        </a>
                        <a href="{{ route('messages.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                            <span>💬</span> Mesaje
                        </a>
                    </div>

                </div>

                {{-- Coloana dreapta: formulare --}}
                <div class="flex-1 space-y-6 min-w-0">

                    {{-- Informații cont --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-semibold text-gray-900 text-sm">Informații cont</h2>
                                <p class="text-xs text-gray-500">Actualizează datele personale și publice</p>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    {{-- Schimbare parolă --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-semibold text-gray-900 text-sm">Schimbare parolă</h2>
                                <p class="text-xs text-gray-500">Folosește o parolă lungă și unică</p>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    {{-- Ștergere cont --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-red-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-semibold text-red-700 text-sm">Ștergere cont</h2>
                                <p class="text-xs text-gray-500">Acțiune permanentă și ireversibilă</p>
                            </div>
                        </div>
                        <div class="p-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
