<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mesaje</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($conversations->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-2xl mb-2">💬</p>
                    <p class="text-lg">Nu ai nicio conversatie inca.</p>
                    <p class="mt-1 text-sm">Contacteaza un meserias sau raspunde unei cereri de serviciu pentru a incepe.</p>
                </div>
            @else
                <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
                    @foreach($conversations as $conv)
                        @php
                            $other = $conv->otherParticipant(auth()->id());
                            $isUnread = $conv->lastMessage
                                && $conv->lastMessage->sender_id !== auth()->id()
                                && $conv->lastMessage->read_at === null;
                        @endphp
                        <a href="{{ route('messages.show', $conv) }}"
                           class="flex items-center gap-4 p-4 hover:bg-gray-50 transition {{ $isUnread ? 'bg-indigo-50' : '' }}">

                            {{-- Avatar --}}
                            <div class="flex-shrink-0 w-11 h-11 rounded-full bg-gray-200 flex items-center justify-center text-xl font-bold text-gray-500">
                                {{ Str::upper(Str::substr($other->name ?? '?', 0, 1)) }}
                            </div>

                            {{-- Continut --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-gray-900 {{ $isUnread ? 'font-bold' : '' }}">
                                        {{ $other->name ?? 'Utilizator sters' }}
                                    </p>
                                    <p class="text-xs text-gray-400 flex-shrink-0">
                                        {{ $conv->last_message_at?->diffForHumans() }}
                                    </p>
                                </div>

                                @if($conv->listing)
                                    <p class="text-xs text-indigo-600">📌 {{ Str::limit($conv->listing->title, 50) }}</p>
                                @elseif($conv->serviceRequest)
                                    <p class="text-xs text-green-600">📋 {{ Str::limit($conv->serviceRequest->title, 50) }}</p>
                                @endif

                                @if($conv->lastMessage)
                                    <p class="text-sm text-gray-600 truncate mt-0.5">
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
