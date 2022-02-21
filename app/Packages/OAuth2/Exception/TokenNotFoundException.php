<?php

namespace App\Packages\OAuth2\Exception;

use App\Exceptions\BaseHttpException;

class TokenNotFoundException extends BaseHttpException
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
        return 'Access denied.';
    }

    protected function code(): int
    {
        return 102;
    }
}
