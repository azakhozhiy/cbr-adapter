<?php

namespace App\Packages\CbrClient\Helper;

use App\Packages\CbrClient\DTO\CourseItem;
use Illuminate\Support\Str;
use SimpleXMLElement;

class CbrHelper
{
    public static function parseCourses(SimpleXMLElement $xmlObject, string $date, ?string $currencyCode = null): array
    {
        $collection = [];

        $currencyCodeLower = $currencyCode ? Str::lower($currencyCode) : null;

        foreach ($xmlObject as $course) {
            $charCode = Str::lower((string) $course->CharCode);

            if ($currencyCodeLower && $currencyCodeLower !== $charCode) {
                continue;
            }

            $value = (string) $course->Value;
            $valueParts = explode(',', $value);

            $valueInteger = $valueParts[0];
            $valueDivision = 0 .'.'.$valueParts[1];
            $fullValue = (float) $valueInteger + (float) $valueDivision;
            $nominal = (int) $course->Nominal;

            $collection[] = new CourseItem($currencyCode ?: $course->CharCode, $fullValue, $nominal, $date);
        }

        return $collection;
    }
}
