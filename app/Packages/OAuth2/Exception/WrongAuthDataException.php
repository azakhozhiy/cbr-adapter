<?php

namespace App\Packages\OAuth2\Exception;

use App\Exceptions\BaseHttpException;

class WrongAuthDataException extends BaseHttpException
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
        return 'Incorrect username or password.';
    }

    protected function code(): int
    {
        return 104;
    }
}
