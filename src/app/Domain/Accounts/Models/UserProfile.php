<?php

namespace App\Domain\Accounts\Models;

use App\Domain\Shared\Models\County;
use App\Domain\Shared\Models\City;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'public_name',
        'company_name',
        'slug',
        'bio',
        'county_id',
        'city_id',
        'address_line',
        'latitude',
        'longitude',
        'avatar_path',
        'cover_path',
        'website',
        'whatsapp_phone',
    ];

    protected function casts(): array
    {
        return [
            'latitude'    => 'float',
            'longitude'   => 'float',
        ];
    }

    // ── Relatii ─────────────────────────────────────────────
    public function user()   { return $this->belongsTo(User::class); }
    public function county() { return $this->belongsTo(County::class); }
    public function city()   { return $this->belongsTo(City::class); }

    // ── Accessors ────────────────────────────────────────────
    public function getDisplayNameAttribute(): string
    {
        return $this->public_name ?? $this->company_name ?? $this->user->name;
    }
}
