<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class SavingsHelper
{
    public static function validateUkSavingsAccountAnnualSummary(array $interest): array
    {
        if (empty($interest['taxedUkInterest'])) {
            unset($interest['taxedUkInterest']);
        } else {
            $interest['taxedUkInterest'] = self::validateFloat("Taxed Interest", $interest['taxedUkInterest']);
        }

        if (empty($interest['untaxedUkInterest'])) {
            unset($interest['untaxedUkInterest']);
        } else {
            $interest['untaxedUkInterest'] = self::validateFloat("Untaxed Interest", $interest['untaxedUkInterest']);
        }

        return $interest;
    }

    public static function validateAndFormatSavingsIncome(array $savings_income): array
    {
        // Remove completely empty rows first
        $savings_income['securities'] = self::removeEmptyRows($savings_income['securities'] ?? []);
        $savings_income['foreignInterest'] = self::removeEmptyRows($savings_income['foreignInterest'] ?? []);

        // If all sections are now empty, bail early
        if (
            empty($savings_income['securities']) &&
            empty($savings_income['foreignInterest'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $savings_income;
        }

        // SECURITIES

        foreach (['taxTakenOff', 'grossAmount', 'netAmount'] as $reference) {

            if (!empty($savings_income['securities'][$reference])) {

                $formatted_ref = Helper::formatCamelCase($reference);

                $savings_income['securities'][$reference] =
                    self::validateFloat("Securities - $formatted_ref", $savings_income['securities'][$reference]);
            }
        }

        // gross amount required
        if ($savings_income['securities']['grossAmount'] === '' || $savings_income['securities']['grossAmount'] === null) {
            self::saveError("Securities - Gross Amount is required.");
        }

        // FOREIGN INTEREST

        if (!empty($savings_income['foreignInterest'])) {
            foreach ($savings_income['foreignInterest'] as $key => $entry) {

                $row_number = $key + 1;

                // country code required
                if (!Validate::countryCode($entry['countryCode'] ?? '')) {
                    self::saveError("Foreign Interest item {$row_number} - A valid Country Code is required.");
                }

                // number values
                foreach (['amountBeforeTax', 'taxTakenOff', 'specialWithholdingTax', 'taxableAmount'] as $field) {
                    if (!empty($savings_income['foreignInterest'][$key][$field])) {
                        $savings_income['foreignInterest'][$key][$field] =
                            self::validateFloat("Foreign Interest item {$row_number} - " . Helper::formatCamelCase($field), $entry[$field]);
                    }
                }

                // taxableAmount is required
                if ($entry['taxableAmount'] === '' || $entry['taxableAmount'] === null) {
                    self::saveError("Foreign Interest item {$row_number} - Taxable Amount is required.");
                }

                // foreign tax credit relief
                $savings_income['foreignInterest'][$key]['foreignTaxCreditRelief'] =
                    !empty($entry['foreignTaxCreditRelief']);
            }
        }

        $savings_income = self::removeEmptyValues($savings_income);

        return $savings_income;
    }


    private static function validateFloat($field, $number, $min = 0, $max = 99999999999.99)
    {
        if (!is_numeric($number) || $number < $min || $number > $max) {
            self::saveError("{$field} must be a number between 0 and 999999999999.99");
            return null;
        } else {
            return round((float)$number, 2);
        }
    }

    private static function removeEmptyRows(array $data): array
    {
        foreach ($data as $key => $entry) {
            if (is_array($entry) && Helper::recursiveArrayEmpty($entry)) {
                unset($data[$key]);
            }

            if (!is_array($entry) && trim((string)$entry) === '') {
                unset($data[$key]);
            }
        }
        return $data;
    }

    private static function removeEmptyValues(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::removeEmptyValues($value);
                // If the array ends up empty after recursion, remove it entirely
                if ($data[$key] === []) {
                    unset($data[$key]);
                }
            } elseif ($value === '' || $value === null) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
