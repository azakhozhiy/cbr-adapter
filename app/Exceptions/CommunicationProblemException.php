<?php

namespace App\Exceptions;

class CommunicationProblemException extends BaseHttpException
{
    public function getStatusCode(): int
    {
        return 200;
    }

    public function getHeaders(): array
    {
        return [];
    }

    protected function message(): string
    {
        return 'Communication problem with Cbr service.';
    }

    protected function code(): int
    {
        return 110;
    }
}
