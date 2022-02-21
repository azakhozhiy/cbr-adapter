<?php

namespace App\Packages\OAuth2\Repository;

use App\Packages\OAuth2\Models\Token;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TokenRepository
{
    public function getByValue(string $value): Builder|Model|Token
    {
        return $this->query()
            ->where('value', $value)
            ->where('is_expired', false)
            ->firstOrFail();
    }

    public function query(): Builder
    {
        return Token::query();
    }
}
