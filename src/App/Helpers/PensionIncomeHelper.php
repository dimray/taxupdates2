<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class PensionIncomeHelper
{


    public static function validateAndFormatPensionsIncome(array $pensions_income): array
    {
        // Remove completely empty rows first
        $pensions_income['foreignPensions'] = self::removeEmptyRows($pensions_income['foreignPensions'] ?? []);
        $pensions_income['overseasPensionContributions'] = self::removeEmptyRows($pensions_income['overseasPensionContributions'] ?? []);


        // If both sections are now empty, bail early
        if (
            empty($pensions_income['foreignPensions']) &&
            empty($pensions_income['overseasPensionContributions'])
        ) {
            self::saveError("At least one Foreign Pension or Overseas Pension Contribution must be entered before submitting.");
            return $pensions_income;
        }

        // FOREIGN PENSIONS
        if (!empty($pensions_income['foreignPensions'])) {

            foreach ($pensions_income['foreignPensions'] as $key => $entry) {

                $row_number = $key + 1;

                // Skip completely empty entries
                if (Helper::recursiveArrayEmpty($entry)) {
                    unset($pensions_income['foreignPensions'][$key]);
                    continue;
                }

                // countryCode is required
                if (!Validate::countryCode($entry['countryCode'] ?? '')) {
                    self::saveError("Foreign Pensions - A country must be selected if data is submitted.");
                }

                // taxableAmount is required
                if ($entry['taxableAmount'] === '' || $entry['taxableAmount'] === null) {
                    self::saveError("Foreign Pensions - Taxable Amount is required if other information is present.");
                } else {
                    $pensions_income['foreignPensions'][$key]['taxableAmount'] =
                        self::validateFloat("Foreign Pensions item {$row_number} - Taxable Amount", $entry['taxableAmount']);
                }

                // Optional numeric entries
                foreach (['amountBeforeTax', 'taxTakenOff', 'specialWithholdingTax'] as $field) {
                    if ($entry[$field] !== '' && $entry[$field] !== null) {
                        $pensions_income['foreignPensions'][$key][$field] =
                            self::validateFloat("Foreign Pensions item {$row_number} - " . Helper::formatCamelCase($field), $entry[$field]);
                    } else {
                        unset($pensions_income['foreignPensions'][$key][$field]);
                    }
                }

                // Boolean conversion
                $pensions_income['foreignPensions'][$key]['foreignTaxCreditRelief'] =
                    !empty($entry['foreignTaxCreditRelief']) ? true : false;
            }
        }

        // OVERSEAS PENSION CONTRIBUTIONS
        if (!empty($pensions_income['overseasPensionContributions'])) {
            foreach ($pensions_income['overseasPensionContributions'] as $key => $entry) {
                $row_number = $key + 1;

                if (Helper::recursiveArrayEmpty($entry)) {
                    unset($pensions_income['overseasPensionContributions'][$key]);
                    continue;
                }

                // Required exemptEmployersPensionContribs
                if ($entry['exemptEmployersPensionContribs'] === '' || $entry['exemptEmployersPensionContribs'] === null) {
                    self::saveError("Overseas Contributions - Exempt Employer Contributions are required.");
                } else {
                    $pensions_income['overseasPensionContributions'][$key]['exemptEmployersPensionContribs'] =
                        self::validateFloat("Overseas Contributions item {$row_number} - Exempt Employer Contributions", $entry['exemptEmployersPensionContribs']);
                }

                // Optional references
                foreach (['customerReference', 'migrantMemReliefQopsRefNo', 'dblTaxationArticle', 'dblTaxationTreaty', 'sf74reference'] as $reference) {
                    if (!empty($entry[$reference])) {
                        $formatted_ref = Helper::formatCamelCase($reference);
                        $pensions_income['overseasPensionContributions'][$key][$reference] =
                            self::validateReference("Overseas Contributions item {$row_number} - {$formatted_ref}", $entry[$reference]);
                    }
                }

                // Optional numerics
                foreach (['dblTaxationRelief'] as $field) {
                    if ($entry[$field] !== '' && $entry[$field] !== null) {
                        $pensions_income['overseasPensionContributions'][$key][$field] =
                            self::validateFloat("Overseas Contributions item {$row_number} - " . Helper::formatCamelCase($field), $entry[$field]);
                    } else {
                        unset($pensions_income['overseasPensionContributions'][$key][$field]);
                    }
                }

                // Optional country code
                if (!empty($entry['dblTaxationCountryCode'])) {
                    if (!Validate::countryCode($entry['dblTaxationCountryCode'])) {
                        self::saveError("Overseas Contributions item {$row_number} - Invalid Country Code.");
                    } else {
                        $pensions_income['overseasPensionContributions'][$key]['dblTaxationCountryCode'] =
                            strtoupper($entry['dblTaxationCountryCode']); // normalise to uppercase if needed
                    }
                }
            }
        }

        $pensions_income = self::removeEmptyValues($pensions_income);

        return $pensions_income;
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

    private static function validateReference($field, $reference)
    {

        // If the reference is not provided or is an empty string
        if (empty($reference)) {
            self::saveError("{$field} - Your Reference is required.");
            return null;
        }

        // Check for maximum length (using mb_strlen for multi-byte character support)
        if (mb_strlen($reference) > 90) {
            self::saveError("{$field} - Your Reference must not exceed 90 characters.");
            return null;
        }

        // Check against the HMRC regular expression
        // The 'u' modifier at the end makes it compatible with UTF-8 characters
        // The regex validates for a specific set of characters and a length of 1 to 90
        $pattern = '/^[0-9a-zA-Z{À-˿’}\- _&`():.\'^]{1,90}$/u';
        if (!preg_match($pattern, $reference)) {
            self::saveError("{$field} - Your Reference contains invalid characters.");
            return null;
        }

        // If all checks pass, return the validated reference
        return $reference;
    }

    private static function removeEmptyRows(array $data): array
    {
        foreach ($data as $key => $entry) {
            if (Helper::recursiveArrayEmpty($entry)) {
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
