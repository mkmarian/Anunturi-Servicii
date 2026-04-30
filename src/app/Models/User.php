<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
        'phone',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Roluri ──────────────────────────────────────────────
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isModerator(): bool  { return in_array($this->role, ['admin', 'moderator']); }
    public function isCraftsman(): bool  { return $this->role === 'craftsman'; }
    public function isCustomer(): bool   { return $this->role === 'customer'; }

    // ── Scopes ──────────────────────────────────────────────
    public function scopeActive($q)     { return $q->where('status', 'active'); }
    public function scopeRole($q, $role){ return $q->where('role', $role); }

    // ── Relatii ─────────────────────────────────────────────
    public function profile()
    {
        return $this->hasOne(\App\Domain\Accounts\Models\UserProfile::class);
    }

    public function listings()
    {
        return $this->hasMany(\App\Domain\Listings\Models\Listing::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(\App\Domain\Requests\Models\ServiceRequest::class);
    }

    public function favorites()
    {
        return $this->hasMany(\App\Domain\Listings\Models\Favorite::class);
    }

    public function reviewsGiven()
    {
        return $this->hasMany(\App\Domain\Reviews\Models\Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(\App\Domain\Reviews\Models\Review::class, 'craftsman_id');
    }

    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->reviewsReceived()->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    public function initiatedConversations()
    {
        return $this->hasMany(\App\Domain\Messaging\Models\Conversation::class, 'initiator_id');
    }

    public function receivedConversations()
    {
        return $this->hasMany(\App\Domain\Messaging\Models\Conversation::class, 'recipient_id');
    }

    public function loginHistory()
    {
        return $this->hasMany(\App\Domain\Accounts\Models\LoginHistory::class);
    }

    public function craftsmanApplications()
    {
        return $this->hasMany(\App\Domain\Accounts\Models\CraftsmanApplication::class);
    }

    public function latestCraftsmanApplication()
    {
        return $this->hasOne(\App\Domain\Accounts\Models\CraftsmanApplication::class)->latestOfMany();
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Domain\Monetization\Models\Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(\App\Domain\Monetization\Models\Subscription::class)
            ->where('status', 'active')
            ->latestOfMany('starts_at');
    }
}
