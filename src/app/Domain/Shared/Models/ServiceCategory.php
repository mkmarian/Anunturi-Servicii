<?php

namespace App\Domain\Shared\Models;

use App\Domain\Listings\Models\Listing;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    // ── Relatii ─────────────────────────────────────────────
    public function parent()
    {
        return $this->belongsTo(ServiceCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ServiceCategory::class, 'parent_id')->orderBy('sort_order');
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'category_id');
    }

    // ── Scopes ──────────────────────────────────────────────
    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopeRoots($q)     { return $q->whereNull('parent_id'); }
    public function scopeOrdered($q)   { return $q->orderBy('sort_order'); }
}
