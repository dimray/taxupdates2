<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class ChargesHelper
{

    public static function validateAndFormatPensionCharges(array $pension_charges): array
    {
        // Remove completely empty rows first
        $pension_charges['pensionSchemeOverseasTransfers'] = self::removeEmptyRows($pension_charges['pensionSchemeOverseasTransfers'] ?? []);
        $pension_charges['pensionSchemeUnauthorisedPayments'] = self::removeEmptyRows($pension_charges['pensionSchemeUnauthorisedPayments'] ?? []);
        $pension_charges['pensionContributions'] = self::removeEmptyRows($pension_charges['pensionContributions'] ?? []);
        $pension_charges['overseasPensionContributions'] = self::removeEmptyRows($pension_charges['overseasPensionContributions'] ?? []);

        // Bail early if everything is empty
        if (
            empty($pension_charges['pensionSchemeOverseasTransfers']) &&
            empty($pension_charges['pensionSchemeUnauthorisedPayments']) &&
            empty($pension_charges['pensionContributions']) &&
            empty($pension_charges['overseasPensionContributions'])
        ) {
            self::saveError("Add information for at least one section before submitting.");
            return $pension_charges;
        }

        // --- PENSION SCHEME OVERSEAS TRANSFERS ---
        if (!empty($pension_charges['pensionSchemeOverseasTransfers'])) {
            $section = &$pension_charges['pensionSchemeOverseasTransfers'];

            if (!isset($section['overseasSchemeProvider'])) {
                self::saveError("Overseas Transfers - provider details are required if other information has been entered.");
            } else {
                foreach ($section['overseasSchemeProvider'] as $key => $entry) {
                    $entry = self::validateOverseasProvider($entry, "Overseas Transfers", $key);
                    $section['overseasSchemeProvider'][$key] = $entry;
                }
            }

            // Transfer charge and tax paid required if providers exist
            if (!empty($section['overseasSchemeProvider'])) {
                $section['transferCharge'] = self::validateRequiredFloat("Overseas Transfers - Transfer Charge", $section['transferCharge'] ?? null);
                $section['transferChargeTaxPaid'] = self::validateRequiredFloat("Overseas Transfers - Transfer Charge Tax Paid", $section['transferChargeTaxPaid'] ?? null);
            }

            if (empty($section['overseasSchemeProvider']) && empty($section['transferCharge']) && empty($section['transferChargeTaxPaid'])) {
                unset($pension_charges['pensionSchemeOverseasTransfers']);
            }
        }

        // --- PENSION SCHEME UNAUTHORISED PAYMENTS ---
        if (!empty($pension_charges['pensionSchemeUnauthorisedPayments'])) {
            $section = &$pension_charges['pensionSchemeUnauthorisedPayments'];

            $section['pensionSchemeTaxReference'] = self::normaliseTaxRefs($section['pensionSchemeTaxReference'] ?? []);
            foreach ($section['pensionSchemeTaxReference'] as $i => $taxRef) {
                if (!preg_match('/^[0-9]{8}[A-Z]{2}$/', $taxRef)) {
                    self::saveError("Unauthorised Payments - Scheme Reference item {$i} must be 8 digits followed by 2 uppercase letters.");
                }
            }

            // Surcharge amounts
            foreach (['surcharge', 'noSurcharge'] as $s) {
                if (!empty($section[$s])) {
                    $section[$s]['amount'] = self::validateRequiredFloat("Unauthorised Payments - {$s} Amount", $section[$s]['amount'] ?? null);
                    $section[$s]['foreignTaxPaid'] = self::validateRequiredFloat("Unauthorised Payments - {$s} Foreign Tax Paid", $section[$s]['foreignTaxPaid'] ?? null);
                }
            }
        }

        // --- PENSION CONTRIBUTIONS ---
        if (!empty($pension_charges['pensionContributions'])) {
            $section = &$pension_charges['pensionContributions'];

            $section['pensionSchemeTaxReference'] = self::normaliseTaxRefs($section['pensionSchemeTaxReference'] ?? []);
            foreach ($section['pensionSchemeTaxReference'] as $i => $taxRef) {
                if (!preg_match('/^[0-9]{8}[A-Z]{2}$/', $taxRef)) {
                    self::saveError("Pension Contributions - Tax Reference item {$i} must be 8 digits followed by 2 uppercase letters.");
                }
            }

            $section['inExcessOfTheAnnualAllowance'] = self::validateRequiredFloat("Pension Contributions - Excess Over Annual Allowance", $section['inExcessOfTheAnnualAllowance'] ?? null);
            $section['annualAllowanceTaxPaid'] = self::validateRequiredFloat("Pension Contributions - Annual Allowance Tax Paid", $section['annualAllowanceTaxPaid'] ?? null);

            $section['isAnnualAllowanceReduced'] = !empty($section['isAnnualAllowanceReduced']);
            $section['taperedAnnualAllowance'] = !empty($section['taperedAnnualAllowance']);
            $section['moneyPurchasedAllowance'] = !empty($section['moneyPurchasedAllowance']);

            if ($section['isAnnualAllowanceReduced'] && !$section['taperedAnnualAllowance'] && !$section['moneyPurchasedAllowance']) {
                self::saveError("Pension Contributions - If Annual Allowance is reduced, select at least one of Tapered or Money Purchased Allowance.");
            }
        }

        // --- OVERSEAS PENSION CONTRIBUTIONS ---
        if (!empty($pension_charges['overseasPensionContributions'])) {
            $section = &$pension_charges['overseasPensionContributions'];

            if (!isset($section['overseasSchemeProvider'])) {
                self::saveError("Overseas Contributions - provider details are required if other information has been entered.");
            } else {
                foreach ($section['overseasSchemeProvider'] as $key => $entry) {
                    $entry = self::validateOverseasProvider($entry, "Overseas Contributions", $key);
                    $section['overseasSchemeProvider'][$key] = $entry;
                }
            }

            $section['shortServiceRefund'] = self::validateRequiredFloat("Overseas Contributions - Short Service Refund", $section['shortServiceRefund'] ?? null);
            $section['shortServiceRefundTaxPaid'] = self::validateRequiredFloat("Overseas Contributions - Short Service Refund Tax Paid", $section['shortServiceRefundTaxPaid'] ?? null);
        }

        $pension_charges = self::removeEmptyValues($pension_charges);

        return $pension_charges;
    }

    public static function validateHicbc(array $hicbc): array
    {

        if (Helper::recursiveArrayEmpty($hicbc)) {
            self::saveError("No data entered");
        }

        if (empty($hicbc['amountOfChildBenefitReceived'])) {
            self::saveError("Amount Of Child Benefit Received is required");
        } else {
            $hicbc['amountOfChildBenefitReceived'] = self::validateRequiredFloat("Amount Of Child Benefit Received", $hicbc['amountOfChildBenefitReceived']);
        }

        if (empty($hicbc['numberOfChildren'])) {
            self::saveError("Number Of Children is required");
        } else {
            $hicbc['numberOfChildren'] = self::validateRequiredFloat("Number Of Children", $hicbc['numberOfChildren']);
        }

        if (empty($hicbc['dateChildBenefitEnded'])) {
            unset($hicbc['dateChildBenefitEnded']);
        } else {
            if (!Validate::date($hicbc['dateChildBenefitEnded'])) {
                self::saveError("Date Child Benefit Ended is not in the correct format. Pick a date using the date-picker");
            }
        }

        return $hicbc;
    }

    // ---------------- Helpers ----------------

    private static function validateOverseasProvider(array $entry, string $sectionName, int $key): array
    {
        $row = $key + 1;

        if (empty($entry['providerName'])) {
            self::saveError("{$sectionName} item {$row} Provider Name is required");
        }
        if (!empty($entry['providerName']) && !Validate::string($entry['providerName'], 1, 90)) {
            self::saveError("{$sectionName} item {$row} Provider Name must be 1-90 characters");
        }

        if (empty($entry['providerAddress'])) {
            self::saveError("{$sectionName} item {$row} Provider Address is required");
        }
        if (!empty($entry['providerAddress']) && !Validate::string($entry['providerAddress'], 1, 90)) {
            self::saveError("{$sectionName} item {$row} Provider Address must be 1-90 characters");
        }

        if (!Validate::countryCode($entry['providerCountryCode'] ?? '')) {
            self::saveError("{$sectionName} item {$row} - valid Country Code is required");
        }

        // Normalise and validate pension references
        $srnRefs = self::normaliseTaxRefs($entry['pensionSchemeTaxReference'] ?? []);
        $qropsRefs = self::normaliseTaxRefs($entry['qualifyingRecognisedOverseasPensionScheme'] ?? []);

        // Validate each SRN
        foreach ($srnRefs as $i => $ref) {
            if (!preg_match('/^[0-9]{8}[A-Z]{2}$/', $ref)) {
                self::saveError("{$sectionName} item {$row} - Pension Scheme Tax Reference '{$ref}' is invalid, must be 8 digits followed by 2 uppercase letters.");
            }
        }

        // Validate each QROPS
        foreach ($qropsRefs as $i => $ref) {
            if (!preg_match('/^Q[0-9]{6}$/', $ref)) {
                self::saveError("{$sectionName} item {$row} - QROPS reference '{$ref}' is invalid, must start with 'Q' followed by 6 digits.");
            }
        }

        // Enforce mutual exclusivity
        if (!empty($srnRefs) && !empty($qropsRefs)) {
            self::saveError("{$sectionName} item {$row} - provide either Pension Scheme Tax Reference or QROPS ref, not both. Dropping QROPS refs.");
            $qropsRefs = [];
        }

        $entry['pensionSchemeTaxReference'] = !empty($srnRefs) ? array_values($srnRefs) : null;
        $entry['qualifyingRecognisedOverseasPensionScheme'] = !empty($qropsRefs) ? array_values($qropsRefs) : null;

        return $entry;
    }

    private static function normaliseTaxRefs($value): array
    {
        if (is_array($value)) {
            return array_map('trim', $value);
        }
        if (is_string($value)) {
            return array_values(array_filter(array_map('trim', explode(',', $value))));
        }
        return [];
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
                if ($data[$key] === []) {
                    unset($data[$key]);
                }
            } elseif ($value === '' || $value === null) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    private static function validateRequiredFloat(string $field, $number, $min = 0, $max = 99999999999.99)
    {
        if ($number === '' || $number === null) {
            self::saveError("{$field} is required");
            return null;
        }
        if (!is_numeric($number) || $number < $min || $number > $max) {
            self::saveError("{$field} must be a number between {$min} and {$max}");
            return null;
        }
        return round((float)$number, 2);
    }

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
