<?php

declare(strict_types=1);

namespace App\Helpers;

use DateTime;
use DateTimeZone;
use DateTimeImmutable;

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

    public static function getMonthsInTaxYear(string $tax_year): array
    {
        [$start_year, $end_year] = explode('-', $tax_year);
        // immutable so it doesn't change when the months change
        $start_date = new DateTimeImmutable("{$start_year}-04-06");

        $periods = [];

        for ($i = 0; $i < 12; $i++) {
            $from = $start_date->modify("+{$i} months");
            $to = $from->modify('+1 month')->modify('-1 day');

            $periods[] = [
                'from' => $from->format('Y-m-d'),
                'to'   => $to->format('Y-m-d'),
            ];
        }

        return $periods;
    }

    public static function hasTaxYearEnded(string $tax_year): bool
    {
        $end_date_str = self::getTaxYearEndDate($tax_year);
        $end_date = new DateTime($end_date_str, new DateTimeZone('Europe/London'));
        $today = new DateTime('now', new DateTimeZone('Europe/London'));

        return $today > $end_date;
    }

    public static function getLatestTaxYear(string $year1, string $year2): string
    {
        // 1. Extract the numeric start year from the first tax year string
        // e.g., '2026-27' becomes 2026
        $start_year_1 = (int) substr($year1, 0, 4);

        // 2. Extract the numeric start year from the second tax year string
        // e.g., '2028-29' becomes 2028
        $start_year_2 = (int) substr($year2, 0, 4);

        // 3. Compare the start years. The larger number corresponds to the later tax year.
        if ($start_year_1 >= $start_year_2) {
            return $year1;
        } else {
            return $year2;
        }
    }

    public static function getTaxYearFromDate(string $date_string): string
    {
        if (empty($date_string)) {
            return "";
        }
        // 1. Convert the date string into a DateTime object
        // Assumes input format is standard YYYY-MM-DD
        $date = new DateTime($date_string);
        $year = (int) $date->format('Y');

        // Define the cutoff date: 5 April of the current year
        $tax_year_end_cutoff = new DateTime("$year-04-05");

        // 2. Determine the Tax Year start/end years

        // If the date is BEFORE or ON 5 April (e.g., Jan 2026, Apr 5 2026)
        // The date belongs to the tax year that ENDS in $year.
        // Example: 02 Jan 2026 is in the 2025-26 tax year.
        if ($date <= $tax_year_end_cutoff) {
            $start_year = $year - 1;
            $end_year = $year;
        }
        // If the date is AFTER 5 April (e.g., Apr 6 2026, Dec 2026)
        // The date belongs to the tax year that STARTS in $year.
        // Example: 06 Apr 2026 is in the 2026-27 tax year.
        else {
            $start_year = $year;
            $end_year = $year + 1;
        }

        // 3. Format the result
        // Get the last two digits of the end year (e.g., 2027 becomes 27)
        $end_year_short = substr((string) $end_year, -2);

        return "{$start_year}-{$end_year_short}";
    }
}
