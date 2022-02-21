<?php

namespace App\Exceptions;

class BadCourseRequestException extends BaseHttpException
{
    public function getStatusCode(): int
    {
        return 400;
    }

    public function getHeaders(): array
    {
        return [];
    }

    protected function message(): string
    {
        return 'Bad course request.';
    }

    protected function code(): int
    {
        return 100;
    }
}
