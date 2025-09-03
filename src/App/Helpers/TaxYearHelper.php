<?php

declare(strict_types=1);

namespace App\Helpers;

use DateTime;

class TaxYearHelper
{

    public static function getCurrentTaxYear(int $offset = 0): string
    {
        $today = new DateTime();
        $year = (int) $today->format('Y');
        $month = (int) $today->format('m');
        $day = (int) $today->format('d');

        // Determine the base tax year start
        $start_year = ($month < 4 || ($month == 4 && $day < 6)) ? $year - 1 : $year;
        // Apply the offset 
        $start_year += $offset;
        $end_year_short = substr((string) ($start_year + 1), -2);
        return "{$start_year}-{$end_year_short}";
    }
}
