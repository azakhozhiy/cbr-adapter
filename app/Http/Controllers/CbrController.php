<?php

namespace App\Http\Controllers;

use App\API\Results\CoursesResult;
use App\DTO\CourseRequest;
use App\Services\CourseCollector;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CbrController extends Controller
{
    public function getCourses(Request $request, CourseCollector $courseCollector): JsonResponse
    {
        $courseRequest = CourseRequest::createByRequest($request);

        $this->logger->info("Input data", $courseRequest->toArray());

        $errorResponse = null;

        $courses = collect();

        try {
            $courses = $courseCollector->setLogger($this->logger)
                ->collect($courseRequest);
        } catch (ClientException $e) {
            $this->logger->info("Client exception, error: {$e->getMessage()}.");
            $errorResponse = $e->getResponse()->getBody()->getContents();
        } catch (Throwable $e) {
            $this->logger->info("Unknown error: {$e->getMessage()}.");
            $errorResponse = $e->getMessage();
        }

        $result = (new CoursesResult())
            ->setStartsAt($courseRequest->getStartsAt())
            ->setEndsAt($courseRequest->getEndsAt())
            ->setItems($courses);

        if ($errorResponse) {
            $result->setError($errorResponse);
        }

        $this->logger->info("Output data", $result->toArray());

        return response()->json($result->toArray());
    }
}
