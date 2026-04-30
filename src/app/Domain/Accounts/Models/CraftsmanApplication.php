<?php

namespace App\Domain\Accounts\Models;

use App\Domain\Shared\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CraftsmanApplication extends Model
{
    protected $fillable = [
        'user_id',
        'service_category_id',
        'experience_years',
        'description',
        'status',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at'      => 'datetime',
            'experience_years' => 'integer',
        ];
    }

    // ── Relatii ─────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopePending($q)  { return $q->where('status', 'pending'); }
    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopeRejected($q) { return $q->where('status', 'rejected'); }

    // ── Helpers ──────────────────────────────────────────────
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'  => 'În așteptare',
            'approved' => 'Aprobată',
            'rejected' => 'Respinsă',
            default    => $this->status,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pending'  => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default    => 'gray',
        };
    }
}
