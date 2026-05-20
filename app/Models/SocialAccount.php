<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    protected $table = 'social_accounts';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',
        'token_expires_at',
        'provider_data'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'provider_data' => 'array'
    ];

    protected $hidden = [
        'provider_token',
        'provider_refresh_token'
    ];

    // Provider constants
    const PROVIDER_GOOGLE = 'google';
    const PROVIDER_GITHUB = 'github';
    const PROVIDER_TWITTER = 'twitter';
    const PROVIDER_TELEGRAM = 'telegram';

    public static function getProviders(): array
    {
        return [
            self::PROVIDER_GOOGLE,
            self::PROVIDER_GITHUB,
            self::PROVIDER_TWITTER,
            self::PROVIDER_TELEGRAM
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }

        return $this->token_expires_at->isPast();
    }
}
