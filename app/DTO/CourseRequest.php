<?php

namespace App\DTO;

use App\Exceptions\BadCourseRequestException;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;

class CourseRequest implements Arrayable
{
    private Carbon $startsAt;
    private ?Carbon $endsAt = null;
    private ?string $currencyCode = null;

    public static function createByRequest(Request $request): self
    {
        $courseRequest = new self();


        $startsAt = $request->get('startsAt');

        if (!$startsAt) {
            throw new BadCourseRequestException();
        }

        $courseRequest->startsAt = Carbon::parse($request->get('startsAt'));
        $endsAt = $request->get('endsAt');
        $courseRequest->endsAt = $endsAt ? Carbon::parse($endsAt) : null;
        $courseRequest->currencyCode = $request->get('currencyCode');

        return $courseRequest;
    }

    public function getDiffInDays(): ?int
    {
        if (!$this->endsAt) {
            return null;
        }

        return $this->getStartsAt()->diffInDays($this->getEndsAt());
    }

    public function getStartsAt(): Carbon
    {
        return $this->startsAt;
    }

    public function getEndsAt(): ?Carbon
    {
        return $this->endsAt;
    }

    public function toArray(): array
    {
        return [
            'startsAt' => $this->startsAt->toDateString(),
            'endsAt' => $this->endsAt?->toDateString(),
            'currencyCode' => $this->getCurrencyCode()
        ];
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }
}
