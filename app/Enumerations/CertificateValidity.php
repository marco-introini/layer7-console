<?php

namespace App\Enumerations;

use Carbon\Carbon;

enum CertificateValidity: string
{
    case MONTHS6 = '6 Months';
    case YEAR1 = '1 Year';
    case YEAR5 = '5 Years';
    case YEAR10 = '10 Years';

    public static function getValues(): array
    {
        $values = array_column(self::cases(), 'value');
        $names = array_column(self::cases(), 'value');
        return array_combine($names, $values);
    }

    public function getExpirationDate(): Carbon
    {
        return match ($this) {
            self::MONTHS6 => now()->addMonths(6),
            self::YEAR1 => now()->addYear(),
            self::YEAR5 => now()->addYears(5),
            self::YEAR10 => now()->addYears(10),
        };
    }
}
