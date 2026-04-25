<x-app-layout
    :seoTitle="$serviceRequest->title"
    :seoDescription="Str::limit(strip_tags($serviceRequest->description ?? ''), 160)"
    ogType="article"
    :canonical="route('service-requests.show', $serviceRequest->slug)"
>
    <x-slot name="header">
        <nav class="text-sm text-gray-500">
            <a href="{{ route('service-requests.index') }}" class="hover:underline">Cereri servicii</a>
            &rsaquo; {{ $serviceRequest->category->name }}
            &rsaquo; <span class="text-gray-800">{{ Str::limit($serviceRequest->title, 60) }}</span>
        </nav>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Coloana principala --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $serviceRequest->title }}</h1>

                    <div class="mt-3 flex flex-wrap gap-3 text-sm text-gray-600">
                        <span>📍 {{ $serviceRequest->city->name }}, {{ $serviceRequest->county->name }}</span>
                        <span>📂 {{ $serviceRequest->category->name }}</span>
                        @if($serviceRequest->desired_date)
                            <span>📅 Data dorita: {{ $serviceRequest->desired_date->format(config('marketplace.date_display')) }}</span>
                        @endif
                        <span>🗓 Postat: {{ $serviceRequest->published_at?->format(config('marketplace.date_display')) }}</span>
                    </div>

                    {{-- Budget --}}
                    @if($serviceRequest->budget_from || $serviceRequest->budget_to || $serviceRequest->budget_type)
                        <div class="mt-4 p-3 bg-indigo-50 rounded-md inline-block">
                            <span class="text-sm text-gray-600">Buget estimat: </span>
                            <span class="font-semibold text-indigo-700">
                                @if($serviceRequest->budget_from && $serviceRequest->budget_to)
                                    {{ number_format($serviceRequest->budget_from, 0, ',', '.') }} - {{ number_format($serviceRequest->budget_to, 0, ',', '.') }} RON
                                @elseif($serviceRequest->budget_from)
                                    De la {{ number_format($serviceRequest->budget_from, 0, ',', '.') }} RON
                                @elseif($serviceRequest->budget_to)
                                    Maxim {{ number_format($serviceRequest->budget_to, 0, ',', '.') }} RON
                                @else
                                    {{ ucfirst($serviceRequest->budget_type) }}
                                @endif
                            </span>
                        </div>
                    @endif

                    <div class="mt-5 prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($serviceRequest->description)) !!}
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">

                {{-- Card client --}}
                <div class="bg-white shadow rounded-lg p-5">
                    @php $profile = $serviceRequest->user->profile; @endphp

                    @if($profile?->avatar_path)
                        <img src="{{ asset('uploads/' . $profile->avatar_path) }}" class="w-14 h-14 rounded-full object-cover mb-3">
                    @else
                        <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-2xl mb-3">👤</div>
                    @endif

                    <h3 class="font-semibold text-gray-900">{{ $profile?->display_name ?? $serviceRequest->user->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $serviceRequest->responses_count }} raspuns(uri) primite</p>
                </div>

                {{-- Raspunde (meseriasii) --}}
                @auth
                    @if(auth()->user()->isCraftsman())
                        <div class="bg-white shadow rounded-lg p-5">
                            <h3 class="font-semibold text-gray-900 mb-3">Esti interesat?</h3>
                            <form action="{{ route('messages.start.request', $serviceRequest) }}" method="POST">
                            @csrf
                            <button type="submit"
                               class="flex items-center justify-center gap-2 w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                                ✉ Contacteaza clientul
                            </button>
                        </form>
                        </div>
                    @endif
                @else
                    <div class="bg-white shadow rounded-lg p-5">
                        <p class="text-sm text-gray-600 mb-3">Esti meserias si poti ajuta?</p>
                        <a href="{{ route('login') }}"
                           class="flex items-center justify-center w-full px-4 py-2 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-50 font-medium">
                            Autentifica-te pentru a raspunde
                        </a>
                    </div>
                @endauth

                {{-- Raporteaza --}}
                @auth
                    <div class="text-center">
                        <a href="#" class="text-xs text-gray-400 hover:text-red-500">🚩 Raporteaza cererea</a>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</x-app-layout>
