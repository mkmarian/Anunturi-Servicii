<?php

namespace App\Domain\Requests\Models;

use App\Domain\Shared\Models\County;
use App\Domain\Shared\Models\City;
use App\Domain\Shared\Models\ServiceCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'county_id',
        'city_id',
        'title',
        'slug',
        'description',
        'budget_type',
        'budget_from',
        'budget_to',
        'currency',
        'status',
        'moderation_note',
        'published_at',
        'expires_at',
        'responses_count',
    ];

    protected function casts(): array
    {
        return [
            'user_id'      => 'integer',
            'budget_from'  => 'decimal:2',
            'budget_to'    => 'decimal:2',
            'published_at' => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopePublished($q)
    {
        return $q->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeActive($q)
    {
        return $q->published()->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    public function scopeInCity($q, int $cityId)    { return $q->where('city_id', $cityId); }
    public function scopeInCounty($q, int $countyId){ return $q->where('county_id', $countyId); }
    public function scopeInCategory($q, int $id)    { return $q->where('category_id', $id); }

    // ── Relatii ─────────────────────────────────────────────
    public function user()     { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(ServiceCategory::class); }
    public function county()   { return $this->belongsTo(County::class); }
    public function city()     { return $this->belongsTo(City::class); }
}
