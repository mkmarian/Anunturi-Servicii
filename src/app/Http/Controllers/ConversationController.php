<?php

namespace App\Http\Controllers;

use App\Domain\Listings\Models\Listing;
use App\Domain\Messaging\Models\Conversation;
use App\Domain\Messaging\Models\Message;
use App\Domain\Requests\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\NewMessageReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    // ── Lista conversatii ────────────────────────────────────
    public function index()
    {
        $me = Auth::id();

        $conversations = Conversation::with([
                'initiator.profile',
                'recipient.profile',
                'lastMessage',
                'listing:id,title,slug',
                'serviceRequest:id,title,slug',
            ])
            ->forUser($me)
            ->orderByDesc('last_message_at')
            ->paginate(15);

        return view('messages.index', compact('conversations'));
    }

    // ── Incepe o conversatie din pagina unui anunt ───────────
    public function startFromListing(Listing $listing)
    {
        $me = Auth::user();

        // Nu poti discuta cu tine insuti
        if ($listing->user_id === $me->id) {
            return back()->with('error', 'Nu poti trimite un mesaj propriului anunt.');
        }

        $conversation = $this->findOrCreateConversation(
            initiatorId: $me->id,
            recipientId: $listing->user_id,
            listingId: $listing->id,
        );

        return redirect()->route('messages.show', $conversation);
    }

    // ── Incepe o conversatie din pagina unei cereri ──────────
    public function startFromRequest(ServiceRequest $serviceRequest)
    {
        $me = Auth::user();

        if ($serviceRequest->user_id === $me->id) {
            return back()->with('error', 'Nu poti raspunde propriei cereri.');
        }

        $conversation = $this->findOrCreateConversation(
            initiatorId: $me->id,
            recipientId: $serviceRequest->user_id,
            serviceRequestId: $serviceRequest->id,
        );

        return redirect()->route('messages.show', $conversation);
    }

    // ── Afiseaza o conversatie ───────────────────────────────
    public function show(Conversation $conversation)
    {
        $me = Auth::id();
        $this->authorizeConversation($conversation, $me);

        // Marcheaza ca citite mesajele primite de mine
        $conversation->messages()
            ->where('sender_id', '!=', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()
            ->with('sender:id,name')
            ->get();

        $other = $conversation->otherParticipant($me);

        return view('messages.show', compact('conversation', 'messages', 'other'));
    }

    // ── Trimite un mesaj (POST, returneaza JSON pentru polling) ─
    public function store(Request $request, Conversation $conversation)
    {
        $me = Auth::id();
        $this->authorizeConversation($conversation, $me);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = null;

        DB::transaction(function () use ($validated, $conversation, $me, &$message) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $me,
                'body'            => $validated['body'],
                'message_type'    => 'text',
            ]);

            $conversation->update([
                'last_message_at' => $message->created_at,
                'last_message_id' => $message->id,
            ]);
        });

        // Trimite email destinatarului dacă nu a mai primit notificare în ultimele 10 minute
        $recipient = $conversation->otherParticipant($me);
        $recentlySentKey = "msg_notif_{$conversation->id}_{$recipient->id}";
        if ($recipient && !cache()->has($recentlySentKey)) {
            $recipient->notify(new NewMessageReceived(
                $conversation->load(['listing:id,title,slug', 'serviceRequest:id,title,slug']),
                Auth::user(),
                $validated['body'],
            ));
            cache()->put($recentlySentKey, true, now()->addMinutes(10));
        }

        if ($request->wantsJson()) {
            $msgs = $conversation->messages()->with('sender:id,name')
                ->orderByDesc('created_at')->limit(1)->get()
                ->map(fn ($m) => $this->formatMessage($m, $me));

            return response()->json(['messages' => $msgs]);
        }

        return redirect()->route('messages.show', $conversation);
    }

    // ── Polling: returneaza mesaje noi dupa un ID ────────────
    public function poll(Request $request, Conversation $conversation)
    {
        $me = Auth::id();
        $this->authorizeConversation($conversation, $me);

        $afterId = (int) $request->query('after', 0);

        $messages = $conversation->messages()
            ->with('sender:id,name')
            ->where('id', '>', $afterId)
            ->get()
            ->map(fn ($m) => $this->formatMessage($m, $me));

        // Marcheaza ca citite
        $conversation->messages()
            ->where('id', '>', $afterId)
            ->where('sender_id', '!=', $me)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }

    // ── Badge: conversatii cu mesaje necitite ────────────────
    public function unreadCount()
    {
        $me = Auth::id();

        $count = Message::whereHas('conversation', function ($q) use ($me) {
                $q->forUser($me);
            })
            ->where('sender_id', '!=', $me)
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    // ── Private helpers ──────────────────────────────────────

    private function findOrCreateConversation(
        int $initiatorId,
        int $recipientId,
        ?int $listingId = null,
        ?int $serviceRequestId = null,
    ): Conversation {
        // Cauta o conversatie existenta intre aceiasi participanti despre acelasi subiect
        $existing = Conversation::where(function ($q) use ($initiatorId, $recipientId) {
                $q->where('initiator_id', $initiatorId)->where('recipient_id', $recipientId);
            })
            ->orWhere(function ($q) use ($initiatorId, $recipientId) {
                $q->where('initiator_id', $recipientId)->where('recipient_id', $initiatorId);
            })
            ->when($listingId, fn ($q) => $q->where('listing_id', $listingId))
            ->when($serviceRequestId, fn ($q) => $q->where('service_request_id', $serviceRequestId))
            ->first();

        if ($existing) {
            return $existing;
        }

        return Conversation::create([
            'initiator_id'       => $initiatorId,
            'recipient_id'       => $recipientId,
            'listing_id'         => $listingId,
            'service_request_id' => $serviceRequestId,
            'started_by_role'    => Auth::user()->role,
            'status'             => 'active',
            'last_message_at'    => now(),
        ]);
    }

    private function authorizeConversation(Conversation $conversation, int $userId): void
    {
        if ($conversation->initiator_id !== $userId && $conversation->recipient_id !== $userId) {
            abort(403, 'Nu ai acces la aceasta conversatie.');
        }
    }

    private function formatMessage(Message $m, int $myId): array
    {
        return [
            'id'      => $m->id,
            'body'    => $m->body,
            'mine'    => $m->sender_id === $myId,
            'sender'  => $m->sender->name ?? 'Utilizator',
            'created' => $m->created_at->format('d-m-Y H:i'),
        ];
    }
}
