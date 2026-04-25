<?php

namespace App\Domain\Monetization\Models;

use App\Domain\Listings\Models\Listing;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PromotedSlot extends Model
{
    protected $fillable = [
        'listing_id',
        'user_id',
        'promotion_type',
        'starts_at',
        'ends_at',
        'status',
        'price_amount',
        'currency',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'    => 'datetime',
            'ends_at'      => 'datetime',
            'price_amount' => 'decimal:2',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('status', 'active')
                 ->where('starts_at', '<=', now())
                 ->where('ends_at', '>', now());
    }

    public function scopeOfType($q, string $type)
    {
        return $q->where('promotion_type', $type);
    }

    // ── Relatii ─────────────────────────────────────────────
    public function listing() { return $this->belongsTo(Listing::class); }
    public function user()    { return $this->belongsTo(User::class); }
}
