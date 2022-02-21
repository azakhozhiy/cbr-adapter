<?php

namespace App\Packages\OAuth2\Exception;

use App\Exceptions\BaseHttpException;

class TokenIsExpiredException extends BaseHttpException
{
    public function getStatusCode(): int
    {
        return 401;
    }

    public function getHeaders(): array
    {
        return [];
    }

    protected function message(): string
    {
        return 'Access denied. Token is expired.';
    }

    protected function code(): int
    {
        return 103;
    }
}
