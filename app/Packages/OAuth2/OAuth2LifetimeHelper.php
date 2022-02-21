<?php

namespace App\Packages\OAuth2;

use App\Packages\OAuth2\Models\Token;
use Carbon\Carbon;
use RuntimeException;

class OAuth2LifetimeHelper
{
    public const LIFETIME_TYPE_D = 'd';
    public const LIFETIME_TYPE_H = 'h';
    public const LIFETIME_TYPE_M = 'm';
    public const LIFETIME_TYPE_INFINITY = 'i';

    public const LIFETIME_TYPES = [
        self::LIFETIME_TYPE_D,
        self::LIFETIME_TYPE_H,
        self::LIFETIME_TYPE_M,
        self::LIFETIME_TYPE_INFINITY
    ];

    public function getExpirationDateFromNow(string $type, int $value = 1): ?Carbon
    {
        if (!in_array($type, self::LIFETIME_TYPES)) {
            throw new RuntimeException("Unknown lifetime type.");
        }

        $date = Carbon::now();

        return match ($type) {
            self::LIFETIME_TYPE_D => $date->addDays($value),
            self::LIFETIME_TYPE_H => $date->addHours($value),
            self::LIFETIME_TYPE_M => $date->addMonths($value),
            self::LIFETIME_TYPE_INFINITY => null,
        };
    }
}
