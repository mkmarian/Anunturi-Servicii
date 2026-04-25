<?php

namespace App\Domain\Shared\Models;

use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    protected $fillable = ['name', 'slug', 'code'];

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
