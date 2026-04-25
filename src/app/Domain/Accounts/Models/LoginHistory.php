<?php

namespace App\Domain\Accounts\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $table = 'login_history';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'country_code',
        'success',
    ];

    protected function casts(): array
    {
        return [
            'success'      => 'boolean',
            'logged_in_at' => 'datetime',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
}
