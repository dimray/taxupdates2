<?php

declare(strict_types=1);

namespace App\Helpers;

use DateTime;
use DateTimeZone;

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

    public static function getPreviousTaxYear(string $tax_year): string
    {
        // Expecting format: "YYYY-YY"
        [$start, $end] = explode('-', $tax_year);

        $previous_start_year = (int) $start - 1;
        $previous_end_year = substr((string) ($previous_start_year + 1), -2);

        return sprintf('%d-%s', $previous_start_year, $previous_end_year);
    }

    public static function getNextTaxYear(string $tax_year): string
    {
        // Expecting format: "YYYY-YY"
        [$start, $end] = explode('-', $tax_year);

        $next_start_year = (int) $start + 1;
        $next_end_year = substr((string) ($next_start_year + 1), -2);

        return sprintf('%d-%s', $next_start_year, $next_end_year);
    }

    public static function getDeadlinesFromTaxYear(string $taxYear): array
    {
        // Example: "2022-23"
        [$startYear, $endShort] = explode('-', $taxYear);

        // Convert to full year (e.g., 23 → 2023)
        $endYear = (int)('20' . $endShort);

        // Define UK timezone
        $ukTimeZone = new DateTimeZone('Europe/London');

        // Filing deadline: 31 Jan (endYear + 1) at 23:59:59 UK time
        $filingDeadline = new DateTime("31 January " . ($endYear + 1) . " 23:59:59", $ukTimeZone);

        // Amendment deadline: 1 year after the filing deadline
        $amendmentDeadline = clone $filingDeadline;
        $amendmentDeadline->modify('+1 year');

        return [
            'filing_deadline' => $filingDeadline->getTimestamp(),
            'amendment_deadline' => $amendmentDeadline->getTimestamp()
        ];
    }

    public static function beforeCurrentYear(string $input_tax_year): bool
    {
        $current_tax_year = self::getCurrentTaxYear();

        $input_start_year = (int) substr($input_tax_year, 0, 4);

        $current_start_year = (int) substr($current_tax_year, 0, 4);

        return $input_start_year < $current_start_year;
    }
}
