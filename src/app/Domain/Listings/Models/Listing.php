<?php

namespace App\Domain\Listings\Models;

use App\Domain\Shared\Models\County;
use App\Domain\Shared\Models\City;
use App\Domain\Shared\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'county_id',
        'city_id',
        'title',
        'slug',
        'short_description',
        'description',
        'price_type',
        'price_from',
        'price_to',
        'currency',
        'phone',
        'show_phone',
        'status',
        'moderation_note',
        'published_at',
        'expires_at',
        'featured_until',
        'views_count',
        'messages_count',
        'favorites_count',
    ];

    protected function casts(): array
    {
        return [
            'price_from'    => 'decimal:2',
            'price_to'      => 'decimal:2',
            'show_phone'    => 'boolean',
            'published_at'  => 'datetime',
            'expires_at'    => 'datetime',
            'featured_until'=> 'datetime',
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

    public function scopeFeatured($q)
    {
        return $q->where('featured_until', '>', now());
    }

    public function scopeInCity($q, int $cityId)
    {
        return $q->where('city_id', $cityId);
    }

    public function scopeInCounty($q, int $countyId)
    {
        return $q->where('county_id', $countyId);
    }

    public function scopeInCategory($q, int $categoryId)
    {
        $ids = \App\Domain\Shared\Models\ServiceCategory::where('id', $categoryId)
            ->orWhere('parent_id', $categoryId)
            ->pluck('id');

        return $q->whereIn('category_id', $ids);
    }

    // ── Relatii ─────────────────────────────────────────────
    public function user()     { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(ServiceCategory::class); }
    public function county()   { return $this->belongsTo(County::class); }
    public function city()     { return $this->belongsTo(City::class); }

    public function images()
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ListingImage::class)->orderBy('sort_order');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function promotedSlots()
    {
        return $this->hasMany(\App\Domain\Monetization\Models\PromotedSlot::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Domain\Reviews\Models\Review::class);
    }

    // ── Accessors ────────────────────────────────────────────
    public function getPriceDisplayAttribute(): string
    {
        $currency = $this->currency ?? 'RON';
        if ($this->price_type === 'free') return 'Gratuit';
        if ($this->price_type === 'negotiable') return 'Negociabil';
        if ($this->price_from && $this->price_to) {
            return number_format($this->price_from, 0, ',', '.') . ' - '
                 . number_format($this->price_to, 0, ',', '.') . ' ' . $currency;
        }
        if ($this->price_from) {
            return 'De la ' . number_format($this->price_from, 0, ',', '.') . ' ' . $currency;
        }
        return 'La cerere';
    }
}
