<?php

namespace App\Packages\OAuth2\Mapping\Results;

use App\Packages\OAuth2\Models\Token;
use Carbon\Carbon;

class AuthorizeResult extends BaseResult
{
    protected string $token;
    protected string $expiredAt;

    public static function createByToken(Token $token): self
    {
        $result = new self();

        $result->token = $token->value;
        $result->expiredAt = Carbon::parse($token->expired_at)->toDateTimeString();

        return $result;
    }

    public function toArray(): array
    {
        return [
            'token' => $this->token,
            'expiredAt' => $this->expiredAt
        ];
    }
}
