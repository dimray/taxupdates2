<?php

declare(strict_types=1);

namespace App\Helpers;

class InsuranceIncomeHelper
{
    public static function validateAndFormatInsuranceIncome(array $insurance_income): array
    {
        // check if empty
        if (Helper::recursiveArrayEmpty($insurance_income)) {
            self::saveError("Please add data before submitting");
            return [];
        }

        foreach ($insurance_income as $section_name => &$entries) {

            if (Helper::recursiveArrayEmpty($entries)) {
                unset($insurance_income[$section_name]);
                continue;
            }

            // validate each of lifeInsurance, capitalRedemptions, lifeAnnuity, voidedIsa, foreign
            foreach ($entries as &$entry) {

                self::validateInsuranceSection($entry, $section_name);
            }
        }

        return $insurance_income;
    }

    private static function validateInsuranceSection(array &$entry, string $section_name)
    {

        if (!isset($entry['gainAmount']) || $entry['gainAmount'] === "" || $entry['gainAmount'] === null) {
            self::saveError(Helper::formatCamelCase($section_name) . ": Gain Amount is required where other information has been entered");

            return;
        }


        if (isset($entry['customerReference'])) {
            $entry['customerReference'] = self::validateReference("$section_name - Customer Reference", $entry['customerReference']);
        }

        if (isset($entry['event'])) {
            $entry['event'] = self::validateReference("$section_name - Event", $entry['event']);
        }

        if (isset($entry['gainAmount'])) {
            $entry['gainAmount'] = self::validateFloat("$section_name - Gain Amount", $entry['gainAmount']);
        }

        $arrays_with_tax_paid = ['lifeInsurance', 'capitalRedemption', 'lifeAnnuity'];

        if (in_array($section_name, $arrays_with_tax_paid)) {

            if (isset($entry['taxPaid'])) {
                $entry['taxPaid'] = true;
            } else {
                $entry['taxPaid'] = false;
            }
        }

        if (isset($entry['yearsHeld'])) {
            $entry['yearsHeld'] = self::validateInt("$section_name - Years Held", $entry['yearsHeld']);
        }

        if (isset($entry['yearsHeldSinceLastGain'])) {
            $entry['yearsHeldSinceLastGain'] = self::validateInt("$section_name - Years Held Since Last Gain", $entry['yearsHeldSinceLastGain']);
        }

        if (isset($entry['deficiencyRelief'])) {
            $entry['deficiencyRelief'] = self::validateFloat("$section_name - Deficiency Relief", $entry['deficiencyRelief']);
        }

        if (isset($entry['taxPaidAmount'])) {
            $entry['taxPaidAmount'] = self::validateFloat("$section_name - Tax Paid Amount", $entry['taxPaidAmount']);
        }
    }

    private static function validateFloat($field, $number, $min = 0, $max = 99999999999.99)
    {
        if ($number === "" || $number === null) {
            return null;
        }

        if (!is_numeric($number) || $number < $min || $number > $max) {
            self::saveError(Helper::formatCamelCase($field) . ": must be a number between $min and $max");
            return null;
        }
        return round((float)$number, 2);
    }

    private static function validateInt($field, $number, $min = 0, $max = 99)
    {
        if ($number === "" || $number === null) {
            return null;
        }

        if (!is_numeric($number) || intval($number) != $number || $number < $min || $number > $max) {
            self::saveError(Helper::formatCamelCase($field) . ":99 must be an integer between $min and $max");
            return null;
        }
        return (int)$number;
    }

    private static function validateReference($field, $reference)
    {
        if ($reference === "" || $reference === null) {
            return null;
        }

        if (empty($reference)) {
            self::saveError(Helper::formatCamelCase($field) . ": A customer reference is required.");
            return null;
        }
        if (mb_strlen($reference) > 90) {
            self::saveError(Helper::formatCamelCase($field) . ": Must not exceed 90 characters.");
            return null;
        }
        $pattern = '/^[0-9a-zA-Z{À-˿’}\- _&():.\'^]{1,90}$/u';
        if (!preg_match($pattern, $reference)) {
            self::saveError(Helper::formatCamelCase($field) . ": Contains invalid characters.");
            return null;
        }
        return $reference;
    }

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
