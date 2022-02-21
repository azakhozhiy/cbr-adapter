<?php

namespace App\Packages\OAuth2\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string $value
 * @property-read int $user_id
 * @property Carbon $expired_at
 * @property bool $is_expired
 *
 * @property-read User $user
 * @see Token::user()
 */
class Token extends Model
{
    protected $table = 'oauth2_tokens';

    protected $hidden = ['value'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isExpired(): bool
    {
        return now()->greaterThan(Carbon::parse($this->expired_at));
    }

    public function makeExpired(): self
    {
        $this->is_expired = true;

        return $this;
    }
}
