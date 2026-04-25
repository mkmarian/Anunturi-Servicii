<?php

namespace App\Domain\Messaging\Models;

use App\Domain\Listings\Models\Listing;
use App\Domain\Requests\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'initiator_id',
        'recipient_id',
        'listing_id',
        'service_request_id',
        'started_by_role',
        'status',
        'last_message_at',
        'last_message_id',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopeActive($q)    { return $q->where('status', 'active'); }

    public function scopeForUser($q, int $userId)
    {
        return $q->where('initiator_id', $userId)
                 ->orWhere('recipient_id', $userId);
    }

    // ── Relatii ─────────────────────────────────────────────
    public function initiator()      { return $this->belongsTo(User::class, 'initiator_id'); }
    public function recipient()      { return $this->belongsTo(User::class, 'recipient_id'); }
    public function listing()        { return $this->belongsTo(Listing::class); }
    public function serviceRequest() { return $this->belongsTo(ServiceRequest::class); }
    public function lastMessage()    { return $this->belongsTo(Message::class, 'last_message_id'); }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    // ── Helpers ──────────────────────────────────────────────
    public function otherParticipant(int $myUserId): ?User
    {
        return $this->initiator_id === $myUserId ? $this->recipient : $this->initiator;
    }
}
