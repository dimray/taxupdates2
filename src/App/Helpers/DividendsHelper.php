<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class DividendsHelper
{
    public static function formatDividendIncome(array $other_dividends): array
    {
        foreach ($other_dividends as $key => $dividends) {
            if (Helper::recursiveArrayEmpty($dividends)) {
                unset($other_dividends[$key]);
                continue;
            }
        }

        if (isset($other_dividends['foreignDividend'])) {
            foreach ($other_dividends['foreignDividend'] as $key => $foreign_dividend) {
                if (Helper::recursiveArrayEmpty($other_dividends['foreignDividend'][$key])) {
                    unset($other_dividends['foreignDividend'][$key]);
                } else {
                    $other_dividends['foreignDividend'][$key] = self::validateDividends("Foreign Dividends", $foreign_dividend);
                }
            }
        }

        if (isset($other_dividends['dividendIncomeReceivedWhilstAbroad'])) {
            foreach ($other_dividends['dividendIncomeReceivedWhilstAbroad'] as $key => $dividend_whilst_abroad) {
                $other_dividends['dividendIncomeReceivedWhilstAbroad'][$key] = self::validateDividends("Dividends Received While Abroad", $dividend_whilst_abroad);
            }
        }

        // --- Refactored and corrected validation for the four similar dividend types ---
        $dividend_types = [
            'stockDividend' => 'Stock Dividends',
            'redeemableShares' => 'Redeemable Shares',
            'bonusIssueOfSecurities' => 'Bonus Issues of Securities',
            'closeCompanyLoansWrittenOff' => 'Close Company Loans Written Off',
        ];

        foreach ($dividend_types as $key => $category) {
            // Only process if the key exists in the data
            if (isset($other_dividends[$key])) {
                $reference = $other_dividends[$key]['customerReference'] ?? null;
                $gross_amount = $other_dividends[$key]['grossAmount'] ?? null;

                // Rule 1: If grossAmount is present, then customerReference is required.
                if (!empty($gross_amount)) {
                    // Validate the gross amount (it's present, so check format)
                    $other_dividends[$key]['grossAmount'] = self::validateFloat($category . " gross amount", $gross_amount);

                    // Now, check the conditional required field: customerReference
                    if (!empty($reference)) {
                        // Reference is present, so validate it.
                        $other_dividends[$key]['customerReference'] = self::validateReference($category . " customer reference", $reference);
                    } else {
                        // Reference is NOT present, so add a required error.
                        self::saveError("{$category} - A customer reference is required when a gross amount is provided.");
                        $other_dividends[$key]['customerReference'] = null; // Set to null on failure
                    }
                } else {
                    // Rule 2: If grossAmount is NOT present, remove the entire entry.
                    // This handles both the "leave both blank" and the "reference without grossAmount" cases.
                    unset($other_dividends[$key]);
                }
            }
        }
        return $other_dividends;
    }

    private static function validateDividends(string $category, array $dividend): array
    {
        $optional_number_fields = [
            'amountBeforeTax',
            'taxTakenOff',
            'specialWithholdingTax',
        ];

        $required_number_field = 'taxableAmount';

        if (!Validate::countryCode($dividend['countryCode'])) {
            self::saveError("$category - A Country Code must be selected from the dropdown box");
        }

        if (isset($dividend['foreignTaxCreditRelief'])) {
            $dividend['foreignTaxCreditRelief'] = true;
        } else {
            $dividend['foreignTaxCreditRelief'] = false;
        }

        if (isset($dividend[$required_number_field]) && !empty($dividend[$required_number_field])) {
            $value = $dividend[$required_number_field];
            if (!is_numeric($value) || $value < 0 || $value > 999999999999.99) {
                self::saveError("{$category} - {$required_number_field} must be a number between 0 and 999999999999.99");
            } else {
                $dividend[$required_number_field] = round((float)$value, 2);
            }
        } else {
            // This is the error message for the required field
            self::saveError("{$category} - {$required_number_field} is required.");
        }

        // --- Validation for the optional number fields ---
        foreach ($optional_number_fields as $field) {
            // Check if the field is present and not empty
            if (isset($dividend[$field]) && !empty($dividend[$field])) {
                $value = $dividend[$field];
                if (!is_numeric($value) || $value < 0 || $value > 999999999999.99) {
                    // If it exists but is invalid, add an error
                    self::saveError("{$category} - {$field} must be a number between 0 and 999999999999.99");
                } else {
                    // If it exists and is valid, format it
                    $dividend[$field] = round((float)$value, 2);
                }
            } else {
                // If the optional field is not present or is empty, unset it
                unset($dividend[$field]);
            }
        }

        return $dividend;
    }

    private static function validateReference($field, $reference)
    {

        // If the reference is not provided or is an empty string
        if (empty($reference)) {
            self::saveError("{$field} - A customer reference is required.");
            return null;
        }

        // Check for maximum length (using mb_strlen for multi-byte character support)
        if (mb_strlen($reference) > 90) {
            self::saveError("{$field} - The customer reference must not exceed 90 characters.");
            return null;
        }

        // Check against the HMRC regular expression
        // The 'u' modifier at the end makes it compatible with UTF-8 characters
        // The regex validates for a specific set of characters and a length of 1 to 90
        $pattern = '/^[0-9a-zA-Z{À-˿’}\- _&`():.\'^]{1,90}$/u';
        if (!preg_match($pattern, $reference)) {
            self::saveError("{$field} - The customer reference contains invalid characters.");
            return null;
        }

        // If all checks pass, return the validated reference
        return $reference;
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

    public static function validateUkDividends(array $uk_dividends): array
    {
        $validated = [];

        $fields = ['ukDividends', 'otherUkDividends'];

        foreach ($fields as $field) {
            if (!isset($uk_dividends[$field]) || $uk_dividends[$field] === '') {
                continue;
            }

            $value = $uk_dividends[$field];

            if (!is_numeric($value)) {
                self::saveError("The value for $field must be a number.");
                continue;
            }

            $value = round((float)$value, 2);

            if ($value < 0 || $value > 99999999999.99) {
                self::saveError("The value for $field must be between 0 and 99999999999.99.");
                continue;
            }

            $validated[$field] = $value;
        }

        return $validated;
    }

    // COMPANY DIRECTOR

    public static function validateDirectorInfo(array $director_info): array
    {
        $validated = [];

        if (isset($director_info['companyDirector'])) {
            $validated['companyDirector'] = true;
        } else {
            $validated['companyDirector'] = false;
        }

        if (isset($director_info['closeCompany'])) {
            $validated['closeCompany'] = true;
        } else {
            $validated['closeCompany'] = false;
        }

        if (!empty($director_info['directorshipCeasedDate'])) {
            if (Validate::date($director_info['directorshipCeasedDate'])) {
                $validated['directorshipdCeasedDate'] = $director_info['directorshipCeasedDate'];
            } else {
                self::saveError("Date directorship ceased must either be blank or in date format. Please use the date picker if inputting a date");
            }
        }

        if ($validated['closeCompany']) {
            if (empty($director_info['companyName'])) {
                self::saveError("Company Name is required where the company is a close company");
            }

            if (empty($director_info['companyNumber'])) {
                self::saveError("Company Number is required where the company is a close company");
            }

            if (empty($director_info['shareholding'])) {
                self::saveError("Shareholding is required where the company is a close company");
            }

            if (empty($director_info['dividendReceived'])) {
                self::saveError("Dividend Received is required where the company is a close company");
            }
        }

        if (!empty($director_info['companyName'])) {
            if (Validate::string($director_info['companyName'], 0, 160)) {
                $validated['companyName'] = trim($director_info['companyName']);
            } else {
                self::saveError("Company name must not be longer than 160 characters");
            }
        }

        if (!empty($director_info['companyNumber'])) {
            if (preg_match('/^(?:\d{8}|[A-Za-z]{2}\d{6})$/', trim($director_info['companyNumber']))) {
                $validated['companyNumber'] = $director_info['companyNumber'];
            } else {
                self::saveError("Company Number is not in the correct format");
            }
        }

        if (!empty($director_info['shareholding'])) {
            $value = $director_info['shareholding'];

            if (!is_numeric($value)) {
                self::saveError("Shareholding must be a number");
            } elseif ($value < 0 || $value > 100) {
                self::saveError("Shareholding must be a number between 0 and 100");
            } else {

                $validated['shareholding'] = round((float)$value, 2);
            }
        }

        if (!empty($director_info['dividendReceived'])) {
            $value = $director_info['dividendReceived'];

            if (!is_numeric($value)) {
                self::saveError("Dividend Received must be a number");
            } elseif ($value < 0 || $value > 99999999999.99) {
                self::saveError("Dividend Received must be a number between 0 and 99999999999.99");
            } else {

                $validated['dividendReceived'] = round((float)$value, 2);
            }
        }

        return $validated;
    }

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
