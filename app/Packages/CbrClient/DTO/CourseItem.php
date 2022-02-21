<?php

namespace App\Packages\CbrClient\DTO;

use Illuminate\Contracts\Support\Arrayable;

class CourseItem implements Arrayable
{
    private string $currencyCode;
    private int $nominal;
    private float $value;
    private string $date;

    public function __construct(string $currencyCode, float $value, int $nominal, string $date)
    {
        $this->currencyCode = $currencyCode;
        $this->value = $value;
        $this->nominal = $nominal;
        $this->date = $date;
    }

    public function toArray(): array
    {
        return [
            'currencyCode' => $this->getCurrencyCode(),
            'value' => $this->getValue(),
            'nominal' => $this->getNominal(),
            'date' => $this->getDate()
        ];
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getNominal(): int
    {
        return $this->nominal;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
