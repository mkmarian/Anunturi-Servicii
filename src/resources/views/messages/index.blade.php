<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Mesaje</h1>
                <p class="text-sm text-gray-500 mt-1">Conversațiile tale cu meșteșugari și clienți</p>
            </div>

            @if($conversations->isEmpty())
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
                    <div class="text-5xl mb-4">💬</div>
                    <p class="text-lg font-medium text-gray-700">Nu ai nicio conversație</p>
                    <p class="mt-1 text-sm text-gray-400">Contactează un meșteșugar sau răspunde unei cereri pentru a începe.</p>
                    <a href="{{ route('listings.index') }}" class="inline-block mt-5 px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition">
                        Caută meșteșugari
                    </a>
                </div>
            @else
                <div class="bg-white shadow-sm rounded-2xl border border-gray-100 divide-y divide-gray-100 overflow-hidden">
                    @foreach($conversations as $conv)
                        @php
                            $other = $conv->otherParticipant(auth()->id());
                            $isUnread = $conv->lastMessage
                                && $conv->lastMessage->sender_id !== auth()->id()
                                && $conv->lastMessage->read_at === null;
                        @endphp
                        <a href="{{ route('messages.show', $conv) }}"
                           class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition {{ $isUnread ? 'bg-indigo-50/60' : '' }}">

                            {{-- Avatar --}}
                            <div class="flex-shrink-0 w-11 h-11 rounded-full bg-indigo-100 flex items-center justify-center text-lg font-bold text-indigo-600">
                                {{ Str::upper(Str::substr($other->name ?? '?', 0, 1)) }}
                            </div>

                            {{-- Continut --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="font-semibold text-gray-900 text-sm {{ $isUnread ? 'text-indigo-900' : '' }} truncate">
                                        {{ $other->name ?? 'Utilizator șters' }}
                                    </p>
                                    <p class="text-xs text-gray-400 flex-shrink-0">
                                        {{ $conv->last_message_at?->diffForHumans() }}
                                    </p>
                                </div>

                                @if($conv->listing)
                                    <p class="text-xs text-indigo-500 truncate">📌 {{ Str::limit($conv->listing->title, 50) }}</p>
                                @elseif($conv->serviceRequest)
                                    <p class="text-xs text-green-600 truncate">📋 {{ Str::limit($conv->serviceRequest->title, 50) }}</p>
                                @endif

                                @if($conv->lastMessage)
                                    <p class="text-xs text-gray-500 truncate mt-0.5">
                                        @if($conv->lastMessage->sender_id === auth()->id())
                                            <span class="text-gray-400">Tu: </span>
                                        @endif
                                        {{ $conv->lastMessage->body }}
                                    </p>
                                @endif
                            </div>

                            @if($isUnread)
                                <div class="flex-shrink-0 w-2.5 h-2.5 bg-indigo-500 rounded-full"></div>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">{{ $conversations->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
