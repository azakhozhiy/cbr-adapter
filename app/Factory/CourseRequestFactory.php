<?php

namespace App\Factory;

use App\Exception\BadCourseRequestException;
use App\Packages\CbrClient\DTO\DailyRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseRequestFactory
{
    public static function createByRequest(Request $request): DailyRequest
    {
        $startsAt = $request->get('starts_at');
        $startsAt = $startsAt ? Carbon::parse($startsAt) : null;

        $endsAt = $request->get('ends_at');
        $endsAt = $endsAt ? Carbon::parse($endsAt) : null;

        $currencyCode = $request->get('currencyCode');

        if (!$startsAt) {
            throw new BadCourseRequestException(); // todo: make it
        }

        return new DailyRequest(
            $startsAt->format('d/m/Y'),
            $currencyCode,
            $endsAt
        );
    }
}
