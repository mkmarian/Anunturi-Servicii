<x-app-layout>
    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col" style="height: calc(100vh - 120px)">

            {{-- Header conversatie --}}
            <div class="flex items-center gap-3 mb-3">
                <a href="{{ route('messages.index') }}" class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-bold text-indigo-600 flex-shrink-0">
                        {{ Str::upper(Str::substr($other->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm leading-tight">{{ $other->name ?? 'Utilizator' }}</p>
                        @if($conversation->listing)
                            <p class="text-xs text-indigo-500 truncate max-w-xs">📌 {{ Str::limit($conversation->listing->title, 40) }}</p>
                        @elseif($conversation->serviceRequest)
                            <p class="text-xs text-green-600 truncate max-w-xs">📋 {{ Str::limit($conversation->serviceRequest->title, 40) }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Zona mesaje --}}
            <div id="messages-container"
                 class="flex-1 overflow-y-auto bg-white shadow-sm rounded-2xl p-4 space-y-3 border border-gray-100"
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
                  class="mt-3 bg-white border border-gray-200 shadow-sm rounded-2xl p-3 flex gap-2 items-end">
                @csrf
                <textarea id="msg-input" name="body" rows="2" required maxlength="5000"
                          placeholder="Scrie un mesaj..."
                          class="flex-1 block border-gray-200 rounded-xl shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none py-2.5 px-3"></textarea>
                <button type="submit"
                        class="flex-shrink-0 w-10 h-10 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
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
