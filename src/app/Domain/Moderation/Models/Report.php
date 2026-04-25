<?php

namespace App\Domain\Moderation\Models;

use App\Domain\Listings\Models\Listing;
use App\Domain\Messaging\Models\Conversation;
use App\Domain\Messaging\Models\Message;
use App\Domain\Requests\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'target_user_id',
        'listing_id',
        'service_request_id',
        'conversation_id',
        'message_id',
        'reason',
        'details',
        'status',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopeOpen($q)     { return $q->where('status', 'open'); }
    public function scopePending($q)  { return $q->whereIn('status', ['open', 'in_review']); }

    // ── Relatii ─────────────────────────────────────────────
    public function reporter()       { return $this->belongsTo(User::class, 'reporter_id'); }
    public function targetUser()     { return $this->belongsTo(User::class, 'target_user_id'); }
    public function listing()        { return $this->belongsTo(Listing::class); }
    public function serviceRequest() { return $this->belongsTo(ServiceRequest::class); }
    public function conversation()   { return $this->belongsTo(Conversation::class); }
    public function message()        { return $this->belongsTo(Message::class); }
    public function reviewer()       { return $this->belongsTo(User::class, 'reviewed_by'); }
}
