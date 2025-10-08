<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class OtherIncomeHelper
{

    public static function validateAndFormatOtherIncome(array $other_income): array
    {

        // Remove completely empty rows first
        $other_income['postCessationReceipts'] = self::removeEmptyRows($other_income['postCessationReceipts'] ?? []);
        $other_income['businessReceipts'] = self::removeEmptyRows($other_income['businessReceipts'] ?? []);
        $other_income['allOtherIncomeReceivedWhilstAbroad'] = self::removeEmptyRows($other_income['allOtherIncomeReceivedWhilstAbroad'] ?? []);
        $other_income['overseasIncomeAndGains'] = self::removeEmptyRows($other_income['overseasIncomeAndGains'] ?? []);
        $other_income['chargeableForeignBenefitsAndGifts'] = self::removeEmptyRows($other_income['chargeableForeignBenefitsAndGifts'] ?? []);
        $other_income['omittedForeignIncome'] = self::removeEmptyRows($other_income['omittedForeignIncome'] ?? []);


        // If all sections are now empty, bail early
        if (
            empty($other_income['postCessationReceipts']) &&
            empty($other_income['businessReceipts']) &&
            empty($other_income['allOtherIncomeReceivedWhilstAbroad']) &&
            empty($other_income['overseasIncomeAndGains']) &&
            empty($other_income['chargeableForeignBenefitsAndGifts']) &&
            empty($other_income['omittedForeignIncome'])
        ) {
            self::saveError("Something must be entered in at least one section before submitting.");
            return $other_income;
        }

        // POST CESSATION RECEIPTS
        if (!empty($other_income['postCessationReceipts'])) {
            foreach ($other_income['postCessationReceipts'] as $key => $entry) {

                $row_number = $key + 1;

                // strings
                foreach (['customerReference', 'businessName', 'businessDescription', 'incomeSource'] as $reference) {
                    if (!empty($other_income['postCessationReceipts'][$key][$reference])) {

                        $formatted_ref = Helper::formatCamelCase($reference);
                        $other_income['postCessationReceipts'][$key][$reference] = self::validateReference("Post Cessation Receipts Item {$row_number} - {$formatted_ref}", $entry[$reference]);
                    }
                }

                // dateBusinessCeased optional
                if (!empty($entry['dateBusinessCeased'])) {
                    $other_income['postCessationReceipts'][$key]['dateBusinessCeased'] =
                        self::validateDate("Post Cessation Receipts Item {$row_number} - Date Business Ceased", $entry['dateBusinessCeased']);
                }


                // required amount
                if ($entry['amount'] === '' || $entry['amount'] === null) {
                    self::saveError("Post Cessation Receipts Item {$row_number} - Amount is required.");
                } else {
                    $other_income['postCessationReceipts'][$key]['amount'] =
                        self::validateFloat("Post Cessation Receipts Item {$row_number} - Amount", $entry['amount']);
                }

                // required tax year
                if ($entry['taxYearIncomeToBeTaxed'] === '' || $entry['taxYearIncomeToBeTaxed'] === null) {
                    self::saveError("Post Cessation Receipts Item {$row_number} - Tax Year is required.");
                } else {
                    $other_income['postCessationReceipts'][$key]['taxYearIncomeToBeTaxed'] =
                        self::validateTaxYear("Post Cessation Receipts Item {$row_number} - Tax Year", $entry['taxYearIncomeToBeTaxed']);
                }
            }
        }

        // BUSINESS RECEIPTS
        if (!empty($other_income['businessReceipts'])) {
            foreach ($other_income['businessReceipts'] as $key => $entry) {

                $row_number = $key + 1;

                // gross amount required
                if ($entry['grossAmount'] === '' || $entry['grossAmount'] === null) {
                    self::saveError("Business Receipts Item {$row_number} - Gross Amount is required.");
                } else {
                    $other_income['businessReceipts'][$key]['grossAmount'] =
                        self::validateFloat("Business Receipts Item {$row_number} - Amount", $entry['grossAmount']);
                }

                // required tax year
                if ($entry['taxYear'] === '' || $entry['taxYear'] === null) {
                    self::saveError("Business Receipts Item {$row_number} - Tax Year is required.");
                } else {
                    $other_income['businessReceipts'][$key]['taxYear'] =
                        self::validateTaxYear("Business Receipts Item {$row_number} - Tax Year", $entry['taxYear']);
                }
            }
        }

        // OTHER INCOME RECEIVED WHILST ABROAD
        if (!empty($other_income['allOtherIncomeReceivedWhilstAbroad'])) {
            foreach ($other_income['allOtherIncomeReceivedWhilstAbroad'] as $key => $entry) {

                $row_number = $key + 1;

                // country code required
                if (!Validate::countryCode($entry['countryCode'] ?? '')) {
                    self::saveError("Other Income Received Whilst Abroad Item {$row_number} - A valid Country Code is required.");
                }

                // number values
                foreach (['amountBeforeTax', 'taxTakenOff', 'specialWithholdingTax', 'taxableAmount', 'residentialFinancialCostAmount', 'broughtFwdResidentialFinancialCostAmount'] as $field) {
                    if (!empty($other_income['allOtherIncomeReceivedWhilstAbroad'][$key][$field])) {
                        $other_income['allOtherIncomeReceivedWhilstAbroad'][$key][$field] =
                            self::validateFloat("Other Income Received Whilst Abroad Item {$row_number} - " . Helper::formatCamelCase($field), $entry[$field]);
                    }
                }

                // taxableAmount is required
                if ($entry['taxableAmount'] === '' || $entry['taxableAmount'] === null) {
                    self::saveError("Income Received Whilst Abroad Item {$row_number} - Taxable Amount is required.");
                }

                // foreign tax credit relief
                if (!empty($other_income['allOtherIncomeReceivedWhilstAbroad'][$key]['foreignTaxCreditRelief'])) {
                    $other_income['allOtherIncomeReceivedWhilstAbroad'][$key]['foreignTaxCreditRelief'] =
                        !empty($entry['foreignTaxCreditRelief']) ? true : false;
                }
            }
        }

        if (!empty($other_income['overseasIncomeAndGains'])) {

            // required gain amount
            if ($other_income['overseasIncomeAndGains']['gainAmount'] === '' || $other_income['overseasIncomeAndGains']['gainAmount'] === null) {
                self::saveError("Overseas Income And Gains - Gain Amount is required.");
            } else {
                $other_income['overseasIncomeAndGains']['gainAmount'] =
                    self::validateFloat("Overseas Income And Gains - Gain Amount", $other_income['overseasIncomeAndGains']['gainAmount']);
            }
        }

        if (!empty($other_income['chargeableForeignBenefitsAndGifts'])) {
            foreach (['transactionBenefit', 'protectedForeignIncomeSourceBenefit', 'protectedForeignIncomeOnwardGift', 'benefitReceivedAsASettler', 'onwardGiftReceivedAsASettler'] as $field) {
                if (!empty($other_income['chargeableForeignBenefitsAndGifts'][$field])) {
                    $formatted_field = Helper::formatCamelCase($field);
                    $other_income['chargeableForeignBenefitsAndGifts'][$field] = self::validateFloat($formatted_field, $other_income['chargeableForeignBenefitsAndGifts'][$field]);
                }
            }
        }

        if (!empty($other_income['omittedForeignIncome'])) {
            // required amount
            if ($other_income['omittedForeignIncome']['amount'] === '' || $other_income['omittedForeignIncome']['amount'] === null) {
                self::saveError("Omitted Foreign Income - Amount is required.");
            } else {
                $other_income['omittedForeignIncome']['amount'] =
                    self::validateFloat("Omitted Foreign Income - Amount", $other_income['omittedForeignIncome']['amount']);
            }
        }

        $other_income = self::removeEmptyValues($other_income);

        return $other_income;
    }

    private static function validateTaxYear($field, $tax_year)
    {

        // Trim and normalise separators to dash
        $tax_year = trim($tax_year);
        $tax_year = preg_replace('/[^\d\-]/', '-', $tax_year); // replace non-digit/non-dash with dash

        // Validate correct format YYYY-YY
        if (!preg_match('/^\d{4}-\d{2}$/', $tax_year)) {
            self::saveError("$field must be in the format YYYY-YY, e.g. 2019-20");
            return null;
        }

        // Validate that the second part is the following year
        $start_year = (int)substr($tax_year, 0, 4);
        $end_year_short = (int)substr($tax_year, 5, 2);

        if (($start_year + 1) % 100 !== $end_year_short) {
            self::saveError("$field must be in the format YYYY-YY, e.g. 2019-20, and a valid tax year");
            return null;
        }

        return $tax_year;
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
        $pattern = '/^.{1,90}$/u';
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

    private static function validateDate($field, $date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        if (!($d && $d->format($format) === $date)) {
            self::saveError("{$field} must be a valid date in the format {$format}.");
            return null;
        }
        return $date;
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
