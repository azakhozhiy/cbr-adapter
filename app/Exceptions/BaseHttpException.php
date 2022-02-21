<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

abstract class BaseHttpException extends Exception implements HttpExceptionInterface
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?: $this->message(), $code ?: $this->code(), $previous);
    }

    abstract protected function message(): string;

    abstract protected function code(): int;

    public function createJsonResponse(Request $request): JsonResponse
    {
        $exceptionParts = explode('\\', static::class);
        $exceptionName = $exceptionParts[count($exceptionParts) - 1];

        $params = [
            'timestamp' => now()->timestamp,
            'path' => $request->path(),
            'data' => [
                'message' => $this->message(),
                'code' => $this->getCode(),
            ],
            'status' => $this->getStatusCode(),
            'exception' => $exceptionName,
        ];

        return new JsonResponse($params, $this->getStatusCode());
    }
}
