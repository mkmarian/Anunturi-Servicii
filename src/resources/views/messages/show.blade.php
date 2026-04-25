<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('messages.index') }}" class="text-indigo-600 hover:text-indigo-800">← Mesaje</a>
            <span class="text-gray-400">/</span>
            <h2 class="font-semibold text-xl text-gray-800">{{ $other->name ?? 'Utilizator' }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col" style="height: calc(100vh - 180px)">

            {{-- Context anunt / cerere --}}
            @if($conversation->listing)
                <div class="mb-3 p-2 bg-indigo-50 rounded-md text-sm text-indigo-700">
                    📌 Referitor la anuntul:
                    <a href="{{ route('listings.show', $conversation->listing->slug) }}" class="underline font-medium">
                        {{ $conversation->listing->title }}
                    </a>
                </div>
            @elseif($conversation->serviceRequest)
                <div class="mb-3 p-2 bg-green-50 rounded-md text-sm text-green-700">
                    📋 Referitor la cererea:
                    <a href="{{ route('service-requests.show', $conversation->serviceRequest->slug) }}" class="underline font-medium">
                        {{ $conversation->serviceRequest->title }}
                    </a>
                </div>
            @endif

            {{-- Zona mesaje --}}
            <div id="messages-container"
                 class="flex-1 overflow-y-auto bg-white shadow rounded-t-lg p-4 space-y-3"
                 data-conversation="{{ $conversation->id }}"
                 data-last-id="{{ $messages->last()?->id ?? 0 }}"
                 data-my-id="{{ auth()->id() }}">

                @foreach($messages as $msg)
                    @include('messages._bubble', ['msg' => $msg])
                @endforeach
            </div>

            {{-- Formular trimitere --}}
            <form id="send-form"
                  action="{{ route('messages.store', $conversation) }}"
                  method="POST"
                  class="bg-white border-t border-gray-200 shadow rounded-b-lg p-3 flex gap-2">
                @csrf
                <textarea id="msg-input" name="body" rows="2" required maxlength="5000"
                          placeholder="Scrie un mesaj..."
                          class="flex-1 block border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                <button type="submit"
                        class="self-end px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-medium">
                    Trimite
                </button>
            </form>
        </div>
    </div>

    {{-- Scroll la ultimul mesaj --}}
    <script>
    (function () {
        const container = document.getElementById('messages-container');
        const form      = document.getElementById('send-form');
        const input     = document.getElementById('msg-input');
        const convId    = container.dataset.conversation;
        const myId      = parseInt(container.dataset.myId);
        let lastId      = parseInt(container.dataset.lastId) || 0;

        // Scroll la jos
        function scrollBottom() {
            container.scrollTop = container.scrollHeight;
        }
        scrollBottom();

        // Construieste bule de chat
        function buildBubble(msg) {
            const wrap = document.createElement('div');
            wrap.id = 'msg-' + msg.id;
            wrap.className = 'flex ' + (msg.mine ? 'justify-end' : 'justify-start');
            wrap.innerHTML = `
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-2xl text-sm
                    ${msg.mine ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-gray-100 text-gray-900 rounded-bl-none'}">
                    <p>${escHtml(msg.body)}</p>
                    <p class="text-xs mt-1 ${msg.mine ? 'text-indigo-200' : 'text-gray-400'} text-right">${msg.created}</p>
                </div>`;
            return wrap;
        }

        function escHtml(str) {
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
                      .replace(/"/g,'&quot;').replace(/\n/g,'<br>');
        }

        // Trimitere mesaj via AJAX
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const body = input.value.trim();
            if (!body) return;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ body }),
            })
            .then(r => r.json())
            .then(data => {
                data.messages.forEach(msg => {
                    if (!document.getElementById('msg-' + msg.id)) {
                        container.appendChild(buildBubble(msg));
                        lastId = Math.max(lastId, msg.id);
                    }
                });
                input.value = '';
                scrollBottom();
            });
        });

        // Trimitere cu Enter (Shift+Enter = newline)
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });

        // Polling la fiecare 4 secunde
        function poll() {
            fetch(`/mesaje/${convId}/poll?after=${lastId}`, {
                headers: { 'Accept': 'application/json' },
            })
            .then(r => r.json())
            .then(data => {
                let added = false;
                data.messages.forEach(msg => {
                    if (!document.getElementById('msg-' + msg.id)) {
                        container.appendChild(buildBubble(msg));
                        lastId = Math.max(lastId, msg.id);
                        added = true;
                    }
                });
                if (added) scrollBottom();
            })
            .catch(() => {}); // ignora erori de retea
        }

        setInterval(poll, 4000);
    })();
    </script>
</x-app-layout>
