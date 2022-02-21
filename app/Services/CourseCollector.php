<?php

namespace App\Services;

use App\DTO\CourseRequest;
use App\Packages\CbrClient\CbrClient;
use App\Packages\CbrClient\DTO\DailyRequest;
use App\Packages\CbrClient\Helper\CbrHelper;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CourseCollector
{
    private CbrClient $client;
    private LoggerInterface $logger;

    public function __construct(CbrClient $client)
    {
        $this->client = $client;
        $this->logger = new NullLogger();
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function collect(CourseRequest $courseRequest): Collection
    {
        $this->logger->info("CourseCollector is started.");
        $collection = new Collection();

        $startDate = $courseRequest->getStartsAt();
        $countDays = $courseRequest->getStartsAt()->diffInDays($courseRequest->getEndsAt());

        $countDays = $countDays ? ++$countDays : 1;

        $this->logger->info("Count days: {$countDays}.");

        for ($i = 0; $i < $countDays; $i++) {
            $startDate = $i === 0 ? $startDate : $startDate->addDay();
            $startDateFormatted = $startDate->format('d/m/Y');
            $this->logger->info("Get courses for day $startDateFormatted.");
            $coursesInXml = $this->client->getCourses(new DailyRequest($startDateFormatted));
            $courses = collect(CbrHelper::parseCourses($coursesInXml, $startDateFormatted,
                $courseRequest->getCurrencyCode()));
            $collection = $collection->merge($courses);
        }

        return $collection;
    }
}
