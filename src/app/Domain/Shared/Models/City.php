<?php

namespace App\Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'county_id',
        'name',
        'slug',
        'postal_code',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'latitude'  => 'float',
            'longitude' => 'float',
        ];
    }

    public function county()
    {
        return $this->belongsTo(County::class);
    }
}
