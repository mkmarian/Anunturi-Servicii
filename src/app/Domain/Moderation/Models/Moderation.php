<?php

namespace App\Domain\Moderation\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Moderation extends Model
{
    protected $fillable = [
        'moderator_id',
        'entity_type',
        'entity_id',
        'action',
        'reason',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function moderator() { return $this->belongsTo(User::class, 'moderator_id'); }

    public function entity()
    {
        return $this->morphTo(__FUNCTION__, 'entity_type', 'entity_id');
    }
}
