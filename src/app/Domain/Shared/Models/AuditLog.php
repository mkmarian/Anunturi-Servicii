<?php

namespace App\Domain\Shared\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'actor_user_id',
        'event_type',
        'entity_type',
        'entity_id',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata'   => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function actor() { return $this->belongsTo(User::class, 'actor_user_id'); }

    // ── Helper static ────────────────────────────────────────
    public static function record(
        string $eventType,
        ?int $actorId = null,
        ?string $entityType = null,
        ?int $entityId = null,
        array $metadata = []
    ): self {
        return static::create([
            'actor_user_id' => $actorId,
            'event_type'    => $eventType,
            'entity_type'   => $entityType,
            'entity_id'     => $entityId,
            'ip_address'    => request()?->ip(),
            'user_agent'    => request()?->userAgent(),
            'metadata'      => $metadata ?: null,
        ]);
    }
}
