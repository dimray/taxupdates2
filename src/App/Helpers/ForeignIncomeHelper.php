<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class ForeignIncomeHelper
{

    public static function validateAndFormatForeignIncome(array $foreign_income): array
    {
        // check if empty
        if (Helper::recursiveArrayEmpty($foreign_income)) {
            self::saveError("Please add data before submitting");
            return [];
        }
        // Validate 'foreignEarnings' - a single object
        if (isset($foreign_income['foreignEarnings'])) {
            $entry = $foreign_income['foreignEarnings'];

            $reference = $entry['customerReference'] ?? null;
            $earnings = $entry['earningsNotTaxableUK'] ?? null;

            if (!empty($earnings)) {
                $foreign_income['foreignEarnings']['earningsNotTaxableUk'] =
                    self::validateFloat("Foreign Earnings - earningsNotTaxableUK", $earnings);

                if (!empty($reference)) {
                    $foreign_income['foreignEarnings']['customerReference'] =
                        self::validateReference("Foreign Earnings - customerReference", $reference);
                } else {
                    self::saveError("Foreign Earnings - A reference is required when earnings are provided (the reference is your own reference).");
                    $foreign_income['foreignEarnings']['customerReference'] = null;
                }
            } else {
                // If earningsNotTaxableUk is empty, remove the object
                unset($foreign_income['foreignEarnings']);
            }
        }

        // Validate unremittableForeignIncome - an array of objects
        if (isset($foreign_income['unremittableForeignIncome'])) {
            foreach ($foreign_income['unremittableForeignIncome'] as $key => $entry) {
                // Skip completely empty entries
                if (Helper::recursiveArrayEmpty($entry)) {
                    unset($foreign_income['unremittableForeignIncome'][$key]);
                    continue;
                }

                // countryCode is required
                if (!Validate::countryCode($entry['countryCode'] ?? '')) {
                    self::saveError("Unremittable Foreign Income - country must be selected if an amount is entered.");
                }

                // amountInForeignCurrency - required
                if (!empty($entry['amountInForeignCurrency'])) {
                    $foreign_income['unremittableForeignIncome'][$key]['amountInForeignCurrency'] =
                        self::validateFloat("Unremittable Foreign Income #$key - Amount In Foreign Currency", $entry['amountInForeignCurrency']);
                } else {
                    self::saveError(Helper::getCountry($entry['countryCode']) . ": Unremittable Foreign Income amount is required.");
                }

                // amountTaxPaid - optional
                if (!empty($entry['amountTaxPaid'])) {
                    $foreign_income['unremittableForeignIncome'][$key]['amountTaxPaid'] =
                        self::validateFloat("Unremittable Foreign Income #$key - Amount Tax Paid", $entry['amountTaxPaid']);
                } else {
                    unset($foreign_income['unremittableForeignIncome'][$key]['amountTaxPaid']);
                }
            }
        }

        return $foreign_income;
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
            self::saveError("{$field} - A reference is required (this is your own reference).");
            return null;
        }

        // Check for maximum length (using mb_strlen for multi-byte character support)
        if (mb_strlen($reference) > 90) {
            self::saveError("{$field} - The reference must not exceed 90 characters.");
            return null;
        }

        // Check against the HMRC regular expression
        // The 'u' modifier at the end makes it compatible with UTF-8 characters
        // The regex validates for a specific set of characters and a length of 1 to 90
        $pattern = '/^[0-9a-zA-Z{À-˿’}\- _&`():.\'^]{1,90}$/u';
        if (!preg_match($pattern, $reference)) {
            self::saveError("{$field} - The reference contains invalid characters.");
            return null;
        }

        // If all checks pass, return the validated reference
        return $reference;
    }

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
