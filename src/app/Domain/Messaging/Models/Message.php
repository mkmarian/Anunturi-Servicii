<?php

namespace App\Domain\Messaging\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'message_type',
        'read_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at'      => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopeUnread($q)
    {
        return $q->whereNull('read_at');
    }

    // ── Relatii ─────────────────────────────────────────────
    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function sender()       { return $this->belongsTo(User::class, 'sender_id'); }

    // ── Helpers ──────────────────────────────────────────────
    public function isReadBy(int $userId): bool
    {
        return $this->sender_id === $userId || $this->read_at !== null;
    }
}
