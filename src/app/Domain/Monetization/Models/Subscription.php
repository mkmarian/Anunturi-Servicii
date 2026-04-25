<?php

namespace App\Domain\Monetization\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_code',
        'status',
        'starts_at',
        'ends_at',
        'renews_at',
        'price_amount',
        'currency',
        'provider',
        'provider_reference',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'    => 'datetime',
            'ends_at'      => 'datetime',
            'renews_at'    => 'datetime',
            'price_amount' => 'decimal:2',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopeActive($q)  { return $q->where('status', 'active'); }
    public function scopeExpired($q) { return $q->where('status', 'expired'); }

    // ── Helpers ──────────────────────────────────────────────
    public function isActive(): bool
    {
        return $this->status === 'active' && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    // ── Relatii ─────────────────────────────────────────────
    public function user() { return $this->belongsTo(User::class); }
}
