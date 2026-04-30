<x-app-layout
    :seoTitle="$serviceRequest->title"
    :seoDescription="Str::limit(strip_tags($serviceRequest->description ?? ''), 160)"
    ogType="article"
    :canonical="route('service-requests.show', $serviceRequest->slug)"
>
    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="text-xs text-gray-400 mb-5 flex items-center gap-1.5">
                <a href="{{ route('service-requests.index') }}" class="hover:text-indigo-600 transition">Cereri servicii</a>
                <span>›</span>
                <a href="{{ route('service-requests.index', ['category' => $serviceRequest->category->slug ?? '']) }}" class="hover:text-indigo-600 transition">{{ $serviceRequest->category->name }}</a>
                <span>›</span>
                <span class="text-gray-600 font-medium truncate">{{ Str::limit($serviceRequest->title, 50) }}</span>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Coloana principala --}}
                <div class="lg:col-span-2 space-y-5">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h1 class="text-2xl font-bold text-gray-900 leading-snug">{{ $serviceRequest->title }}</h1>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-50 border border-gray-200 px-2.5 py-0.5 rounded-full">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                {{ $serviceRequest->city->name }}, {{ $serviceRequest->county->name }}
                            </span>
                            <span class="flex items-center gap-1 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 px-2.5 py-0.5 rounded-full">
                                {{ $serviceRequest->category->icon ?? '📂' }} {{ $serviceRequest->category->name }}
                            </span>
                            @if($serviceRequest->published_at)
                            <span class="flex items-center gap-1 text-xs text-gray-400 bg-gray-50 border border-gray-200 px-2.5 py-0.5 rounded-full">
                                🗓 {{ $serviceRequest->published_at->format(config('marketplace.date_display')) }}
                            </span>
                            @endif
                        </div>

                        <div class="mt-5 prose prose-sm max-w-none text-gray-600 leading-relaxed">
                            {!! nl2br(e($serviceRequest->description)) !!}
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-4">

                    {{-- Card client --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        @php $profile = $serviceRequest->user->profile; @endphp

                        <div class="flex items-center gap-3 mb-3">
                            @if($profile?->avatar_path)
                                <img src="{{ asset('uploads/' . $profile->avatar_path) }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-indigo-100">
                            @else
                                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-xl font-bold text-indigo-600">
                                    {{ Str::upper(Str::substr($serviceRequest->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="font-semibold text-gray-900 text-sm">{{ $profile?->display_name ?? $serviceRequest->user->name }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $serviceRequest->responses_count }} răspuns{{ $serviceRequest->responses_count == 1 ? '' : 'uri' }} primite
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Raspunde (meseriasii) --}}
                    @auth
                        @if(auth()->user()->isCraftsman())
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1">Ești interesat?</h3>
                                <p class="text-xs text-gray-400 mb-3">Trimite un mesaj clientului și propune-ți serviciile.</p>
                                <form action="{{ route('messages.start.request', $serviceRequest) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                       class="flex items-center justify-center gap-2 w-full px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition text-sm font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                        Contactează clientul
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                            <p class="text-sm text-gray-600 mb-3">Ești meșter și poți ajuta?</p>
                            <a href="{{ route('login') }}"
                               class="flex items-center justify-center w-full px-4 py-2.5 border border-indigo-600 text-indigo-600 rounded-xl hover:bg-indigo-50 transition text-sm font-semibold">
                                Autentifică-te pentru a răspunde
                            </a>
                        </div>
                    @endauth

                    @auth
                        <div class="text-center">
                            <a href="#" class="text-xs text-gray-400 hover:text-red-500 transition">🚩 Raportează cererea</a>
                        </div>
                    @endauth
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
