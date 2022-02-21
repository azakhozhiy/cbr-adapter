<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $container = $this->container;
        $this->reportable(
            function (Throwable $e) use ($container): void {
                if ($this->shouldntReport($e)) {
                    return;
                }

                $logger = $container->make(LoggerInterface::class);

                $logger->error(
                    $e->getMessage(),
                    [
                        $e->getFile(),
                        $e->getLine()
                    ]
                );
            }
        )->stop();

        $this->renderable(
            function (Throwable $e, $request): JsonResponse {
                return $this->isHttpException($e)
                    ? $e->createJsonResponse($request)
                    : new JsonResponse(
                        array_merge(
                            $this->convertExceptionToArray($e),
                            [
                                'timestamp' => now()->timestamp,
                                'path' => $request->path(),
                                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            ]
                        ),
                        Response::HTTP_INTERNAL_SERVER_ERROR
                    );
            }
        );
    }
}
