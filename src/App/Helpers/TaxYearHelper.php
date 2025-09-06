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

    // used in Obligations
    public static function getTaxYearStartDate(string $tax_year): string
    {
        // Split the tax year into its start and end years.
        $years = explode('-', $tax_year);

        $start_year = (int) $years[0];

        // The tax year starts on April 6th of the start year.
        $start_date_string = sprintf('%d-04-06', $start_year);

        // Create a DateTime object.
        $start_date = new DateTime($start_date_string);

        return $start_date->format('Y-m-d');
    }

    // used in Obligations
    public static function getTaxYearEndDate(string $tax_year): string
    {
        $years = explode('-', $tax_year);

        $start_year = (int) $years[0];
        $end_year = $start_year + 1; // Calculate the full end year

        // The tax year ends on April 5th of the end year.
        $end_date_string = sprintf('%d-04-05', $end_year);

        $end_date = new DateTime($end_date_string);

        return $end_date->format('Y-m-d');
    }
}
