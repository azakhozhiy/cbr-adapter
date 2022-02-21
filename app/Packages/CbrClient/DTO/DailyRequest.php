<?php

namespace App\Packages\CbrClient\DTO;

class DailyRequest
{
    private string $date;

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
