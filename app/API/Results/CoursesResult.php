<?php

namespace App\API\Results;

use App\Packages\CbrClient\DTO\CourseItem;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class CoursesResult implements Arrayable
{
    private Collection $items;
    private ?string $error = null;
    private Carbon $startsAt;
    private ?Carbon $endsAt = null;

    public function setItems(Collection $collection): self
    {
        $this->items = $collection;

        return $this;
    }

    public function setStartsAt(Carbon $startsAt): self
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function setEndsAt(?Carbon $endsAt = null): self
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function setError(string $message): self
    {
        $this->error = $message;

        return $this;
    }

    public function toArray(): array
    {
        $data = [];

        /** @var CourseItem $item */
        foreach ($this->items as $item) {
            $data[] = $item->toArray();
        }

        return [
            'data' => $data,
            'startsAt' => $this->startsAt->format('d/m/Y'),
            'endsAt' => $this->endsAt?->format('d/m/Y'),
            'error' => $this->error
        ];
    }
}
