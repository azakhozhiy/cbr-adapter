<?php

namespace App\Packages\OAuth2\Factory;

use App\Models\User;
use App\Packages\OAuth2\Models\Token;
use App\Packages\OAuth2\OAuth2LifetimeHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;

class TokenFactory
{
    private string $lifetimeType;
    private string $lifetimeValue;

    public function __construct(Repository $config, private OAuth2LifetimeHelper $lifetimeHelper)
    {
        $this->lifetimeType = $config->get('oauth2.token_lifetime_type');
        $this->lifetimeValue = $config->get('oauth2.token_lifetime_value');
    }

    public function createByUser(User $user, ?Carbon $expiredAt = null): Token
    {
        $token = new Token();

        $token->user()->associate($user);
        $token->value = hash('sha256', Str::random(40));
        $token->expired_at = ($expiredAt ?: $this->lifetimeHelper->getExpirationDateFromNow($this->lifetimeType,
            $this->lifetimeValue))->endOfDay();
        $token->is_expired = false;

        return $token;
    }
}
