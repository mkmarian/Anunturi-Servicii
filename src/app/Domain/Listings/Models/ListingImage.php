<?php

namespace App\Domain\Listings\Models;

use Illuminate\Database\Eloquent\Model;

class ListingImage extends Model
{
    protected $fillable = [
        'listing_id',
        'path',
        'alt_text',
        'sort_order',
        'width',
        'height',
        'size_bytes',
    ];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
