<?php

declare(strict_types=1);

namespace App\Helpers;

class ExpensesHelper
{
    public static function validateAndFormatEmploymentExpenses(array $employment_expenses)
    {
        // remove empty rows and validate
        foreach ($employment_expenses as $key => $value) {
            if (trim((string) $value) === '') {
                unset($employment_expenses[$key]);
            } else {

                $employment_expenses[$key] = self::validateFloat(Helper::formatCamelCase($key), $value);
            }
        }

        if (empty($employment_expenses)) {
            self::saveError("Please add some data before submitting");
        }

        return $employment_expenses;
    }

    public static function validateAndFormatOtherExpenses($other_expenses)
    {
        // Remove completely empty rows first
        $other_expenses['paymentsToTradeUnionsForDeathBenefits'] = self::removeEmptyRows($other_expenses['paymentsToTradeUnionsForDeathBenefits'] ?? []);
        $other_expenses['patentRoyaltiesPayments'] = self::removeEmptyRows($other_expenses['patentRoyaltiesPayments'] ?? []);

        // TRADE UNION EXPENSES

        if (isset($other_expenses['paymentsToTradeUnionsForDeathBenefits']['customerReference'])) {

            if (trim((string)$other_expenses['paymentsToTradeUnionsForDeathBenefits']['customerReference']) !== "") {
                $other_expenses['paymentsToTradeUnionsForDeathBenefits']['customerReference'] = self::validateReference("Reference", $other_expenses['paymentsToTradeUnionsForDeathBenefits']['customerReference']);
            }
        }

        if (isset($other_expenses['paymentsToTradeUnionsForDeathBenefits']['expenseAmount'])) {

            if (trim((string)$other_expenses['paymentsToTradeUnionsForDeathBenefits']['expenseAmount']) !== "") {
                $other_expenses['paymentsToTradeUnionsForDeathBenefits']['expenseAmount'] = self::validateFloat("Reference", $other_expenses['paymentsToTradeUnionsForDeathBenefits']['expenseAmount']);
            }
        }

        // PATENT ROYALTIES

        if (isset($other_expenses['patentRoyaltiesPayments']['customerReference'])) {

            if (trim((string)$other_expenses['patentRoyaltiesPayments']['customerReference']) !== "") {
                $other_expenses['patentRoyaltiesPayments']['customerReference'] = self::validateReference("Reference", $other_expenses['patentRoyaltiesPayments']['customerReference']);
            }
        }

        if (isset($other_expenses['patentRoyaltiesPayments']['expenseAmount'])) {

            if (trim((string)$other_expenses['patentRoyaltiesPayments']['expenseAmount']) !== "") {
                $other_expenses['patentRoyaltiesPayments']['expenseAmount'] = self::validateFloat("Reference", $other_expenses['patentRoyaltiesPayments']['expenseAmount']);
            }
        }

        if (empty($other_expenses)) {
            self::saveError("Please add some data before submitting");
        }

        return $other_expenses;
    }

    private static function validateFloat($field, $number, $min = 0, $max = 99999999999.99)
    {
        if (!is_numeric($number) || $number < $min || $number > $max) {
            self::saveError("{$field} must be a number between 0 and 99999999999.99");
            return null;
        } else {
            return round((float)$number, 2);
        }
    }

    private static function validateReference($field, $reference)
    {
        // If the reference is not provided or is an empty string
        if (empty($reference)) {
            self::saveError("{$field} - A reference is required.");
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

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
